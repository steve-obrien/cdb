<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Tenant;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
	/**
	 * Display the user's profile form.
	 */
	public function dashboard(Request $request): Response
	{
		return Inertia::render('Dashboard', [
			'tennants' => Tenant::all()
		]);
	}
}
