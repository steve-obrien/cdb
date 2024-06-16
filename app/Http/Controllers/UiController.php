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
			'components' => UiComponent::where('parent_id', '=', null)->orderBy('created_at', 'desc')->get(),
		]);
	}
	public function edit($uiId): Response
	{
		$ui = UiComponent::findOrFail($uiId);
		// get the latest one.
		// $ui->getLatestRevision();
		$ui = UiComponent::where('parent_id', '=', $ui->id)->get()->last();
		return Inertia::render('UiEdit', [
			'ui' => $ui,
		]);
	}

	/**
	 * Sets up a SSE stream event - handles the reciept of large data
	 */
	public function send(Request $request)
	{
		$prompt = $request->post('prompt', '');
		$id = $request->post('id', null);

		if ($id == null) {
			// create a new component
			$ui = UiComponent::create([
				'prompt' => $prompt,
				'user_id' => auth()->user()->id
			]);
		} else {
			// we have a reference to an existing component with existing html
			// therefore this is an instructed revision
			// we want to create a new component but reference its parent.
			// So we can give the parents html as context
			$ui = UiComponent::findOrFail($id);
			$parentId = $ui->parent_id ?? $ui->id;
			// this needs to find the latest revision based on parent id
			$ui = UiComponent::create([
				'prompt' => $prompt,
				'user_id' => auth()->user()->id,
				'parent_id' => $parentId,
			])->fresh();
		}
		return response()->json($ui);
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

		$ai->addMessage('system', $systemPrompt);
		
		// Get the previous version
		// this returns the latest we want the latest -1
		$previous = $ui->getPrevious();
		if ($previous != null) {
			$ai->addMessage('user', $previous->html);
		}

		$ai->addMessage('user', $ui->prompt);

		$handleChunk = function($chunk, $chunks) use($ui) {
			// $nodeltas = Ai::processDeltas($chunks);
			// // add incrementally.
			// $ui->html = $ui->html + Arr::get($nodeltas,'choices.0.content', '');
			// $ui->save();
		};

		$handleFinished = function($result) use($ui) {
			$ui->html = Arr::get($result, 'choices.0.content');
			// maybe save the full prompt
			// $ui->promptSend = $ai->messages
			$ui->save();
		};

		$ai->eventStream($handleChunk, $handleFinished);
	}
}
