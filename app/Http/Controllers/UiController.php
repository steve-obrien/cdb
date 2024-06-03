<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatSession;
use Inertia\Inertia;
use Inertia\Response;
use App\Ai;
use App\Models\UiComponent;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UiController extends Controller
{
	public function ui(Request $request): Response
	{
		return Inertia::render('Ui', [
			'components' => UiComponent::all(),
			'sessions' => ChatSession::orderByDesc('created_at')->get(),
		]);
	}
	public function make(Request $request): Response
	{
		return Inertia::render('UiMake', [
			'chats' => [],
			'sessions' => ChatSession::orderByDesc('created_at')->get(),
		]);
	}

	/**
	 * Sets up a SSE stream event - handles the reciept of large data
	 */
	public function send(Request $request)
	{
		$prompt = $request->post('prompt', '');

		$ui = UiComponent::create([
			'prompt' => $prompt,
			'user_id' => auth()->user()->id
		]);

		return response()->json([
			'uiId' => $ui->id,
		]);
	}

	/**
	 * Return a chat stream
	 * @param string $uiId - ulid of UiComponent
	 * @return void 
	 */
	public function stream($uiId)
	{
		$ui = UiComponent::findOrFail($uiId);

		$ai = new Ai();
		$systemPrompt = "You are a tailwing css html component generator."
		 . "You recieve prompts of component descriptions and you output the html markup only without markdown formatting."
		 . "You must only respond with html components. Do not include html, head, or body tags"
		 . "For images you can use a placeholder src url http://newicon.test/firefly/file/get?w=100"
		 . "You can add ?w and h parameters for width and height values in pixels"
		 . "Only return content within the body tag.";

		$ai->addMessage('system', $systemPrompt)
			->addMessage('user', $ui->prompt);

		$handleChunk = function($chunk, $chunks) {};

		$handleFinished = function($result) use($ui) {
			$ui->html = Arr::get($result, 'choices.0.content');
			$ui->save();
		};

		$ai->eventStream($handleChunk, $handleFinished);

	}

	public function fetch()
	{
		$components = UiComponent::all();
		return response()->json([
			'components' => $components
		]);
	}
}
