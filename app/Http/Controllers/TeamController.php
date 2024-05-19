<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class TeamController extends Controller
{
	public function team(Request $request): Response
	{
		return Inertia::render('Team', [
			'users' => User::all(),
			'invites' => Invitation::all()

		]);
	}

	public function invite(Request $request)
	{
		$request->validate([
			'email' => 'required|email|unique:users,email|unique:invitations,email',
		]);

		$token = Str::random(32);

		$invitation = Invitation::create([
			'name' => $request->name,
			'email' => $request->email,
			'token' => $token,
			'invited_by' => auth()->id(),
		]);

		Mail::to($request->email)->send(new \App\Mail\InvitationMail($invitation));

		return response()->json(['invite' => $invitation]);
	}

	public function inviteAccept($token)
	{
		$invitation = Invitation::where('token', $token)->firstOrFail();

		return Inertia::render('Team/InviteRegister', [
			'invite' => $invitation,
		]);
		// Redirect to registration form with the invitation email
	}

	public function inviteRegister($token, Request $request)
	{
		$invitation = Invitation::where('token', $token)->firstOrFail();

		$request->validate([
			'password' => ['required', Password::min(8)->uncompromised()],
		]);

		// add password to their user
		$user = User::create([
			'name' => $invitation->name,
			'email' => $invitation->email,
			'password' => Hash::make($request->password),
		]);
		Auth::login($user);
		// delete the invite
		// email user -> to let them know?
		$invitation->delete();

		return redirect(RouteServiceProvider::HOME);

	}

	public function inviteDelete($token)
	{
		$invitation = Invitation::where('token', $token)->firstOrFail();
		$invitation->deleteOrFail();
		return response()->json();
	}
}
