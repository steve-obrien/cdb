<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use \OpenAI;
use OpenAI\Resources\Chat as ResourcesChat;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatApiController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function chat(Request $request)
	{
		$prompt = $request->input('prompt');

		$yourApiKey = env('OPEN_AI_KEY');

		$client = OpenAI::factory()
			->withApiKey($yourApiKey)
			// ->withOrganization('newicon') // default: null
			->withHttpClient($client = new \GuzzleHttp\Client([])) // default: HTTP client found using PSR-18 HTTP Client Discovery
			->make();


		Chat::create([
			'user_id' => auth()->user()->id, 
			'content' => $prompt,
			'role' => 'user',
			'name' => auth()->user()->name, 
		]);

		// $messages = [];
		$messages = Chat::select('role','name','content')->get()->toArray();
		$messages[] = ['role' => 'user', 'name'=>auth()->user()->name, 'content' => $prompt];
		foreach($messages as $i => $m) {
			// openAI api can not cope with empty "name" properties
			if (array_key_exists('name', $m) && $m['name'] == null) {
				unset($messages[$i]['name']);
			}
		}

		$stream = $client->chat()->createStreamed([
			'model' => 'gpt-4',
			'messages' => $messages
		]);

		$chunks = [];
		$message = '';

		$response = new StreamedResponse();
		$response->setCallback(function () use ($stream, $chunks, $message) {


			// $message .= $aiMessage['delta']['content'];
			
			foreach ($stream as $response) {
				$responseArray = $response->choices[0]->toArray();
				$message .= Arr::get($responseArray,'delta.content', '');
				$chunks[] = $responseArray;

				echo "event: message\n";
				echo "data: " . json_encode($responseArray) . "\n\n";
				flush();
			}

			// save
			try {
				Chat::create(['user_id' => auth()->user()->id, 'role' => 'assistant','content' => $message, 'chunks' => $chunks]);
			} catch(\Exception $e) {
				echo "event: error\n";
				echo "data: ". $e->__toString() . "\n\n";
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

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
