<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
	public function team(Request $request): Response
	{
		return Inertia::render('Team', [
			'users' => User::all() 
		]);
	}
}
