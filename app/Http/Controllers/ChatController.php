<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
	public function chat(Request $request): Response
	{
		return Inertia::render('Chat', [
			'chats' => Chat::all()
		]);
	}
}
