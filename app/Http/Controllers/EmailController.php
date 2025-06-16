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
use App\Services\GmailService;

class EmailController extends Controller
{
	protected $gmailService;

	public function __construct(GmailService $gmailService)
	{
		$this->gmailService = $gmailService;
	}
	public function email(Request $request)
	{
		$emails = $this->gmailService->getEmails();

		// If the service returns a redirect, handle it here (if no access token exists)
		// If no access token, redirect
		if (!$emails) {
			//if ($request->header('X-Inertia')) {
				// Handle Inertia request with Inertia::location
				return Inertia::location(route('gmail.connect'));
			//}

			// Handle standard web requests
			//return redirect()->route('gmail.connect');
		}

		return Inertia::render('Email', [
			'email' => $emails,
		]);
	}
}
