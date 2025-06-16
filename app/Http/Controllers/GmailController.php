<?php

namespace App\Http\Controllers;

use App\Services\GmailService;
use Illuminate\Http\Request;

class GmailController extends Controller
{
	protected GmailService $gmailService;

	public function __construct(GmailService $gmailService)
	{
		$this->gmailService = $gmailService;
	}

	public function connect()
	{
		if (!session()->has('access_token')) {
			return redirect($this->gmailService->getAuthUrl());
		}

		return redirect()->route('gmail.emails');
	}

	public function callback(Request $request)
	{
		if ($request->has('code')) {
			$token = $this->gmailService->fetchAccessTokenWithAuthCode($request->input('code'));
			session()->put('access_token', $token);
		}

		return redirect()->route('gmail.emails');
	}

	public function getEmails()
	{
		if (session()->has('access_token')) {
			$this->gmailService->setAccessToken(session('access_token'));

			if ($this->gmailService->isAccessTokenExpired()) {
				// Refresh token and update session with the new access token
				$token = $this->gmailService->refreshToken();
				session()->put('access_token', $token);
			}

			$emails = $this->gmailService->getEmails();

			return view('emails', ['emails' => $emails]);
		}

		return redirect()->route('gmail.connect');
	}
}
