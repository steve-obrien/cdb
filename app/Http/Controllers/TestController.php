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

class TestController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function stream(Request $request) {
		$ai = new Ai();
		$ai->addMessage('user', 'Tell me a joke.');
		// $ai->handleChunk(function() {});
		// $ai->handleFinished(function() {})
		$result = $ai->eventStream(null, function($finished) { 
			echo json_encode($finished);
		});
		// $ai->stream();
		dump($result);

	}

	public function merge() {
		$chunks = array(
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("role" => "assistant", "content" => ""), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => "Sure"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => ","), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " here"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " you"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " go"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => ": "), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => "Why"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " don't"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " skeleton"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => "s"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " fight"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " each"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " other"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => "? "), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => "They"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " don't"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " have"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " the"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => " guts"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array("content" => "!"), "logprobs" => NULL, "finish_reason" => NULL)), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(array("index" => 0, "delta" => array(), "logprobs" => NULL, "finish_reason" => "stop")), "usage" => NULL),
			array("id" => "chatcmpl-9VJvEay6AQs2tBnWnycVVDA8l52bU", "object" => "chat.completion.chunk", "created" => 1717251776, "model" => "gpt-4o-2024-05-13", "system_fingerprint" => "fp_319be4768e", "choices" => array(), "usage" => array("prompt_tokens" => 12, "completion_tokens" => 20, "total_tokens" => 32))
		);

		dd(Ai::processDeltas($chunks));
		// Initialize the merged array

		$result = $this->mergeArray($chunks);
		
		dump($result);
		
	}

	function mergeArray($array) {
		// Initialize the merged array
		$merged = [
			"id" => "",
			"object" => "",
			"created" => 0,
			"model" => "",
			"system_fingerprint" => "",
			"choices" => [["index" => 0, "content" => "", "logprobs" => NULL, "finish_reason" => NULL]],
			"usage" => ["prompt_tokens" => 0, "completion_tokens" => 0, "total_tokens" => 0]
		];
	
		// Loop through each item in the array
		foreach ($array as $item) {
			// Merge common attributes (assuming they are the same for all elements)
			$merged['id'] = $item['id'];
			$merged['object'] = $item['object'];
			$merged['created'] = $item['created'];
			$merged['model'] = $item['model'];
			$merged['system_fingerprint'] = $item['system_fingerprint'];
	
			// Concatenate content from delta keys
			if (isset($item['choices'][0]['delta']['content'])) {
				$merged['choices'][0]['content'] .= $item['choices'][0]['delta']['content'];
			}
			
			if (($finish = Arr::get($item, 'choices.0.finish_reason', null)) !== null) {
				$merged['choices'][0]['finish_reason'] = $finish;
			}
	
			// Merge usage data
			if (!empty($item['usage'])) {
				$merged['usage'] = $item['usage'];
			}
		}
	
		return $merged;
	}
	
	public function test() {
		$ai = new Ai();
		$ai->addMessage('user', 'Tell me a joke.');
		$chunks=[];
		$ai->makeChatRequest(function($chunk) use(&$chunks) {
			$chunks[] = json_decode($chunk, true);
		});
	dump($chunks);
		foreach($chunks as $chunk) {

		}
		// dump('here', Ai::processDeltas($chunks));
	}
	public function hello(Request $request)
	{

		$response = new StreamedResponse();
		$response->setCallback(function ()  {


			$api_key = config('services.openai.key');
			$api_url = 'https://api.openai.com/v1/chat/completions';

			$data = [
				'model' => 'gpt-4', // or the appropriate model
				'messages' => [
					['role' => 'system', 'content' => 'You are a helpful assistant.'],
					['role' => 'user', 'content' => 'Tell me a joke.']
				],
				'stream' => true,
				'stream_options' => [
					'include_usage' => true
				]
			];

			$headers = [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $api_key
			];
			

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $api_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
				static $buffer = '';

				// Append new data to buffer
				$buffer .= $data;
		
				// Split buffer into chunks of complete JSON responses
				while (($pos = strpos($buffer, "\n")) !== false) {
		
					$chunk = substr($buffer, 0, $pos);
					$buffer = substr($buffer, $pos + 1);
		
					if (strlen(trim($chunk)) > 0) {
						// $parsed = json_decode($chunk, true);
						echo "event: message\n";
						echo  $chunk . "\n\n";
						@ob_flush();
						flush();
					}
				}
		
				return strlen($data);
			});

			$response = curl_exec($ch);

			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}

			curl_close($ch);

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

	function processStreamData($ch, $data) 
	{
		static $buffer = '';

		// Append new data to buffer
		$buffer .= $data;

		// Split buffer into chunks of complete JSON responses
		while (($pos = strpos($buffer, "\n")) !== false) {

			$chunk = substr($buffer, 0, $pos);
			$buffer = substr($buffer, $pos + 1);

			if (strlen(trim($chunk)) > 0) {
				// $parsed = json_decode($chunk, true);
				echo "event: message\n";
				echo  $chunk . "\n\n";
				@ob_flush();
				flush();
			}
		}

		return strlen($data);
	}
}
