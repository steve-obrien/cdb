<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

		return response()->json(['success' => true]);
	}

	public function inviteAccept($token)
	{
		$invitation = Invitation::where('token', $token)->firstOrFail();

		return Inertia::render('InviteRegister', [
			'users' => User::all()
		]);
		// Redirect to registration form with the invitation email
		return redirect()->route('register')->with('email', $invitation->email);
	}
}
