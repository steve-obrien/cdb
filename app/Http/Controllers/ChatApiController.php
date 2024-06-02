<?php

namespace App\Http\Controllers;

use App\Ai;
use \OpenAI;
use App\Models\Chat;
use App\Models\ChatSession;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatApiController extends Controller
{
	public function chatSessionDelete($sessionId)
	{
		/** @var ChatSession $session */
		$session = ChatSession::findOrFail($sessionId);
		return $session->delete();
	}

	public function chatStart(Request $request)
	{
		// Validate request data

		// Or is it a new session?
		$sessionId = $request->post('sessionId');
		$prompt = json_encode($request->post('prompt', ''));
		if ($sessionId == null) {
			// create a new session
			$chatSession = ChatSession::create(['name' => '', 'created_by' => auth()->user()->id, 'prompt' => $prompt]);
			/** @var ChatSession $chatSession */
			// The first message should be a system message:
			$chatSession->addSystemMessage();
		} else {
			// Find the chat session
			$chatSession = ChatSession::find($sessionId);
			if (!$chatSession) {
				return response()->json(['error' => 'Chat session not found'], 404);
			}
		}

		/** @var ChatSession $chatSession */
		$chat = $chatSession->addUserChat($prompt);

		try {
			event(new \App\Events\ChatMessage($sessionId, $chat));
		} catch (\Throwable $e) {
			report($e);
		}
		return response()->json([
			'sessionId' => $chatSession->id,
			'chat' => $chat->load('user')
		]);
	}

	public function chatStream(string $id)
	{
		/** @var ChatSession $chatSession */
		$chatSession = ChatSession::findOrFail($id);

		$ai = new Ai();
		// add historic messages:
		$ai->addMessages($chatSession->getChatsForOpenAi());

		// Create the chat model - for the ai chat response
		/** @var Chat $chat */
		$chat = $chatSession->chats()->create([
			'user_id' => auth()->user()->id,
			'role' => 'assistant',
			'content' => '',
			'chunks' => []
		]);

		$ai->eventStream(
			function ($chunk, $chunks) use ($chat, $chatSession) {
				$nodeltas = Ai::processDeltas($chunks);
				// lets update the database - we do this per stream which is a little excessive.
				$chat->update([
					'content' => Arr::get($nodeltas,'choices.0.content', ''),
					'chunks' => $chunks,
					'tool_calls' => Arr::get($nodeltas,'choices.0.tool_calls', null),
				]);
				// send push messages to listening clients
				// to avoid lots of network noise and work around Pusher 1024 packet size limit - lets just send the new part:
				try {
					event(new \App\Events\ChatMessageChunk($chatSession->id, $chat, Arr::get($chunk,'choices.0')));
				} catch (\Throwable $e) {
					report($e);
				}
			}, 
			// on finish
			function($response) use($chat) {
				$chat->update(['response' => $response, 'total_tokens' => Arr::get($response,'usage.total_tokens')]);
			},
		);
	}
}
