<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthSessionController extends Controller
{
	/**
	 * Display the login view.
	 */
	public function view(): Response
	{
		return Inertia::render('Auth/Login', [
			'canResetPassword' => Route::has('password.request'),
			'status' => session('status'),
		]);
	}

	/**
	 * Handle an incoming authentication request.
	 */
	public function login(LoginRequest $request): RedirectResponse
	{
		$request->authenticate();

		$request->session()->regenerate();

		return redirect()->intended(RouteServiceProvider::HOME);
	}

	/**
	 * Destroy an authenticated session.
	 */
	public function logout(Request $request): RedirectResponse
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}

	public function redirectToGoogle(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function handleGoogleCallback(): RedirectResponse
	{
		$googleUser = Socialite::driver('google')->user();

		// Attempt to find the user by the email returned by Google.
		/** @var \App\Models\User */
		$user = User::where('email', $googleUser->email)->first();

		// If a user already has an email then this can link to the google account

		if ($user) {
			// The user already exists, link this Google account to the existing user if not already linked.
			$user->update([
				'provider' => 'google',
				'provider_id' => $googleUser->id,
				'provider_token' => $googleUser->token,
				'avatar' => $googleUser->getAvatar(),
				// Update any other fields if necessary.
			]);
		} else {
			// No user exists with this email, create a new user.
			$user = User::create([
				'name' => $googleUser->name,
				'email' => $googleUser->email,
				'provider' => 'google',
				'provider_id' => $googleUser->id,
				'provider_token' => $googleUser->token,
				'avatar' => $googleUser->getAvatar(),
				// Set other fields as required.
			]);
		}

		Auth::login($user);

		return redirect('/dashboard'); // Or wherever you wish to redirect the user to.
	}
}
