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
	
	/**
	 * 
	 */
	// public function chatSessionStart(Request $request)
	// {
	// 	$prompt = $request->post('prompt');
	// 	// create a new session
	// 	$chatSession = ChatSession::create(['name' => '', 'created_by' => auth()->user()->id, 'prompt'=>$prompt]);
	// 	/** @var ChatSession $chatSession */
	// 	// The first message should be a system message:
	// 	$chatSession->addSystemMessage();
	// 	$chat = $chatSession->addUserChat($prompt);
	// 	return [
	// 		'sessionId' => $chatSession->id,
	// 		'chatId' => $chat->id,
	// 	];
	// }

	public function chatStart(Request $request)
	{
		// Validate request data

		// Or is it a new session?
		$sessionId = $request->post('sessionId');
		$prompt = $request->post('prompt', '');
		if ($sessionId == null) {
			// create a new session
			$chatSession = ChatSession::create(['name' => '', 'created_by' => auth()->user()->id, 'prompt'=>$prompt]);
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
		}catch(\Throwable $e) {
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

		$client = $this->getAiClient();

		$stream = $client->chat()->createStreamed([
			'tools' => $this->getAiTools(),
			'model' => $this->getChatGptModel(),
			'messages' => $chatSession->getChatsToOpenAiFormat()
		]);

		$chunks = [];
		$content = '';
		$response = new StreamedResponse();
		$response->setCallback(function () use ($stream, $chunks, $content, $chatSession) {

			try {
				// Create the chat model
				/** @var Chat $chat */
				$chat = $chatSession->chats()->create([
					'user_id' => auth()->user()->id,
					'role' => 'assistant',
					'content' => '',
					'chunks' => $chunks
				]);

				foreach ($stream as $response) {
					$responseArray = $response->choices[0]->toArray();
					$contentChunk = Arr::get($responseArray, 'delta.content', '');
					$content .= $contentChunk;
					$chunks[] = $responseArray;
					// lets update the database - we do this per stream which is a little excessive.
					$chat->update(['content' => $content, 'chunks' => $chunks]);
					// send push messages to listening clients
					// to avoid lots of network noise and work around Pusher 1024 packet size limit - lets just send the new part:
					try {
						event(new \App\Events\ChatMessageChunk($chatSession->id, $chat, $contentChunk));
					} catch(\Throwable $e) {
						report($e);
					}

					echo "event: message\n";
					echo "data: " . json_encode($responseArray) . "\n\n";
					@ob_flush();
					flush();
				}

			} catch (\Exception $e) {
				echo "event: error\n";
				echo "data: " . $e->__toString() . "\n\n";
			}

			// stop the stream
			echo "event: stop\n";
			echo "data: stopped\n\n";
			flush();
		});

		$response->headers->set('Content-Type', 'text/event-stream');
		$response->headers->set('X-Accel-Buffering', 'no');
		$response->headers->set('Cach-Control', 'no-cache');
		$response->send();
	}


	public function getAiClient() {
		return OpenAI::factory()
			->withApiKey(config('services.openai.key'))
			->withHttpClient($client = new \GuzzleHttp\Client([]))
			->make();
	}

	public function getAiTools()
	{
		return [
			[
				"type" => "function",
				"function" => [
					"name" => "get_current_weather",
					"description" => "Get the current weather in a given location",
					"parameters" => [
						"type" => "object",
						"properties"=> [
							"location"=> [
								"type"=> "string",
								"description" => "The city and state, e.g. San Francisco, CA",
							],
							"unit" => ["type" => "string", "enum" => ["celsius", "fahrenheit"]],
						],
						"required" => ["location"],
					],
				]
			]
		];
	}

	/**
	 * Get chatgpt model
	 * @return string chatgpt model
	 */
	public function getChatGptModel()
	{
		return 'gpt-4o';
	}
}
