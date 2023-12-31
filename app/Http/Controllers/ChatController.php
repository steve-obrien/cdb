<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
	public function chat(Request $request): Response
	{
		return Inertia::render('Chat', [
			'chats' => [],
			'sessions' => ChatSession::orderByDesc('created_at')->get(),
		]);
	}

	public function chatSession(string $id): Response
	{
		$session = ChatSession::findOrFail($id);
		return Inertia::render('ChatSession', [
			'chats' => $session->chats()->get(),
			'sessions' => ChatSession::orderByDesc('created_at')->get(),
			'sessionId' => $id,
		]);
	}
}
