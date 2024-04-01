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

		// Here, you'd typically find or create a user in your database.
		// Then, authenticate the user into your application and redirect them.

		$user = User::updateOrCreate([
			'provider_id' => $googleUser->id,
		], [
			'name' => $googleUser->name,
			'email' => $googleUser->email,
			'provider_token' => $googleUser->token,
			
			//'provider_refresh_token' => $googleUser->refreshToken,
		]);

		// 	Auth::login($user);

		// 	return redirect('/dashboard');

		return redirect('/dashboard'); // Or wherever you wish to redirect the user to.
	}
}
