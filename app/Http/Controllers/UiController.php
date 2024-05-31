<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatSession;
use Inertia\Inertia;
use Inertia\Response;
use App\Ai;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UiController extends Controller
{
	public function ui(Request $request): Response
	{
		return Inertia::render('Ui', [
			'chats' => [],
			'sessions' => ChatSession::orderByDesc('created_at')->get(),
		]);
	}

	public function send(Request $request)
	{
		$prompt = $request->get('prompt', '');
		/** @var ChatSession $chatSession */

		$ai = new Ai();

		$ai->addMessage('system', "You are a tailwing css html component generator.
			You recieve prompts of component descriptions and you output the html markup only without markdown formatting")
			->addMessage('user', $prompt);
		
		$ai->stream();
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
						"properties" => [
							"location" => [
								"type" => "string",
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
