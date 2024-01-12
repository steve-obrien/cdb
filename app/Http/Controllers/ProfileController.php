<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Agent;

class ProfileController extends Controller
{
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
	 * Update the user's profile information.
	 */
	public function update(ProfileUpdateRequest $request): RedirectResponse
	{
		$request->user()->fill($request->validated());

		if ($request->user()->isDirty('email')) {
			$request->user()->email_verified_at = null;
		}

		$request->user()->save();

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
