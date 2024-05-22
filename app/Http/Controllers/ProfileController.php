<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Agent;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
	/**
	 * displays public images
	 * @return void 
	 */
	public function avatar($id)
	{
		// Assuming 'public' is the disk configured to point to the 'storage/app/public' folder
		// 'avatars/...' path needs to be inside 'storage/app/public' for 'public' disk
		$avatarPath = "avatars/$id.jpg";

		// Build the full path to the image file
		// $fullImagePath = Storage::disk('public')->path($avatarPath);
		$fullImagePath = storage_path('app/public/' . $avatarPath);

		// Check if the image exists before attempting to return it
		if (file_exists($fullImagePath)) {
			// Return the image file directly as a response
			return response()->file($fullImagePath);
		}

		// Image not found, return a 404 error or your custom error response
		return response()->json(['error' => 'Avatar not found.'], 404);
	}

	/**
	 * Display the user's profile form.
	 */
	public function edit(Request $request): Response
	{
		return Inertia::render('Profile/Edit', [
			'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
			'status' => session('status'),
			'sessions' => $this->sessions($request)->all()
		]);
	}

	/**
	 * Log out from other browser sessions.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroyOtherBrowserSessions(Request $request)
	{
		$request->validate([
			'password' => ['required', 'current_password'],
		]);

		Auth::logoutOtherDevices($request->password);

		$this->deleteOtherSessionRecords($request);

		return back(303);
	}

	/**
	 * Delete the other browser session records from storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return void
	 */
	protected function deleteOtherSessionRecords(Request $request)
	{
		if (config('session.driver') !== 'database') {
			return;
		}

		DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
			->where('user_id', $request->user()->getAuthIdentifier())
			->where('id', '!=', $request->session()->getId())
			->delete();
	}

	/**
	 * Get the current sessions.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Support\Collection
	 */
	public function sessions(Request $request)
	{
		if (config('session.driver') !== 'database') {
			return collect();
		}

		return collect(
			DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
				->where('user_id', $request->user()->getAuthIdentifier())
				->orderBy('last_activity', 'desc')
				->get()
		)->map(function ($session) use ($request) {
			$agent = $this->createAgent($session);

			return (object) [
				'agent' => [
					'is_desktop' => $agent->isDesktop(),
					'platform' => $agent->platform(),
					'browser' => $agent->browser(),
				],
				'ip_address' => $session->ip_address,
				'is_current_device' => $session->id === $request->session()->getId(),
				'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
			];
		});
	}

	/**
	 * Create a new agent instance from the given session.
	 *
	 * @param  mixed  $session
	 * @return \App\Agent
	 */
	protected function createAgent($session)
	{
		return tap(new Agent(), fn ($agent) => $agent->setUserAgent($session->user_agent));
	}

	/**
	 * 
	 * Update the user's profile information.
	 */
	public function update(Request $request): RedirectResponse
	{
		$user = $request->user();
		//dd($request->all());
		// Define validation rules
		$validated = $request->validate([
			'name' => ['string', 'max:255'],
			'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
			'photo' => ['nullable', 'image', 'max:4000'] // Ensure the photo is an image and has a max size
		]);

		// Validate the request
		// Check if a new photo is uploaded
		if ($request->hasFile('photo')) {

			// Store the new photo
			$photoPath = $request->file('photo')->store('avatars', 'public');

			// If the user already has an avatar, delete the old one
			if ($user->avatar) {
				Storage::disk('public')->delete($user->avatar);
			}

			// Update the validated data with the new avatar URL
			$validated['avatar'] = $photoPath;
		}

		// Fill and save the user's profile data
		$user->fill($validated);

		if ($user->isDirty('email')) {
			$user->email_verified_at = null;
		}

		$user->save();

		return Redirect::route('profile.edit');
	}

	/**
	 * Delete the user's account.
	 */
	public function destroy(Request $request): RedirectResponse
	{
		$request->validate([
			'password' => ['required', 'current_password'],
		]);

		$user = $request->user();

		Auth::logout();

		$user->delete();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return Redirect::to('/');
	}
}
