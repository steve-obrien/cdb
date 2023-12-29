<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
	/**
	 * Display the registration view.
	 */
	public function create(): Response
	{
		return Inertia::render('Auth/Register', [
			'host' => config('tenancy.central_domains')
		]);
	}

	/**
	 * Handle an incoming registration request.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request): RedirectResponse
	{
		// Maria/MySQL max DB name length is 64 characters long
		$maxDbLength = 64;
		$maxDb = $maxDbLength - strlen(config('tenancy.database.prefix') . config('tenancy.database.suffix'));

		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:' . User::class,
			'domain' => "required|regex:/^[a-z0-9-_]+$/|string|max:$maxDb|unique:" . Domain::class.',id',
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);

		// create the tenant:
		$tenant = \App\Models\Tenant::create([
			'id' => $request->domain
		]);
		$tenant->domains()->create([
			'domain' => $request->domain . '.' . config('tenancy.central_domains')[0]
		]);

		$tenant->run(function () use($request) {
			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => Hash::make($request->password),
			]);
			event(new Registered($user));

			Auth::login($user);
		});

		// This is a new user 

		

		return redirect(RouteServiceProvider::HOME);
	}
}
