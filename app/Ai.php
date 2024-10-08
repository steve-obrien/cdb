<?php

namespace App;

use Illuminate\Contracts\Container\BindingResolutionException;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use \OpenAI;
use OpenAI\Exceptions\ErrorException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Chat GPT AI object
 * Just a very simple wrapper to group the logic not trying to be fancy here.
 */
class Ai
{

	public $messages = [];

	/**
	 * 
	 * @param string $role - 'system' | 'user'
	 * @param string $content  - the prompt or content of the message
	 * @param string|null $name - an optional user name
	 * @return $this - a chainable method
	 */
	public function addMessage(String $role, String|array $content, $name = null)
	{
		if (is_array($content)) {
			// ensure, if empty, it is an empty string
			foreach($content as $key => $c) {
				if ($c['type'] === 'text' && $c['text'] == null) {
					$content[$key]['text'] = '';
				}
			}
		}
		$message = ['role' => $role, 'content' => $content];
		// Open AI library will error if:
		// - The name key exists but is empty or null value
		// - The name is in the format "Steve OBrien" as it does not allow spaces
		if ($name !== null && $name !== '') {
			// Replace spaces with hyphens and check if the name matches the pattern
			$name = preg_replace('/\s+/', '-', $name);
			if (preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $name)) {
				$message['name'] = $name;
			}
			// if the name still does not match then we will not add it.
		}
		$this->messages[] = $message;
		return $this;
	}

	public function addMessages($messages)
	{
		foreach ($messages as $message) {
			// Extract the array keys into variables
			extract($message);
			// Call addMessage with the extracted variables
			$this->addMessage($role, $content, $name ?? null);
		}
	}

	/**
	 * Output specific event stream response strings.
	 * Encodes the data of each chunk to json.
	 * @param callable|null $callback recieves two parameters $chunk and $chunks
	 * @param callable|null $finished recieves one parameter complete merged result
	 * @param array $options 
	 * @return void 
	 * @throws InvalidArgumentException 
	 */
	public function eventStream(callable $callback = null, callable $finished = null, array $options = [])
	{
		throw_if(empty($this->messages), \BadFunctionCallException::class, 'No messages to send');

		$response = new StreamedResponse();
		
		$response->setCallback(function () use ($callback, $finished, $options) {

			$handleChunk = function ($chunk, $chunks) use ($callback) {
				if (isset($chunk['choices'][0])) {
					echo "event: message\n";
					echo "data: " . json_encode($chunk['choices'][0]) . "\n\n";
					@ob_flush(); flush();
					if ($callback) call_user_func($callback, $chunk, $chunks);
				}
			};

			// $handleError = function ($error) {
			// 	echo "event: error\n";
			// 	echo "data: " . json_encode($error) . "\n\n";
			// 	@ob_flush(); flush();
			// };

			$mergedChunks = $this->makeChatRequest($handleChunk, $options);

			// stop the stream
			echo "event: stop\n";
			echo "data: stopped\n\n";
			flush();

			if ($finished) call_user_func($finished, $mergedChunks);
		});
		$response->headers->set('Content-Type', 'text/event-stream');
		$response->headers->set('X-Accel-Buffering', 'no');
		$response->headers->set('Cach-Control', 'no-cache');
		$response->send();
	}


	/**
	 * An event stream keeps the request open.
	 * A push stream instead creates a job.  
	 * This allows the request to complete.
	 * It returns a uuid that the client can subscribe to via a websocket.
	 * A job is added that makes a request to the API.
	 * This job sends the stream data to a push event.
	 * And calls a complete job with the finished output.
	 * @return void 
	 */
	public function pushStream()
	{
	}

	/**
	 * Responsible for making the call to OpenAI ChatGPT.
	 * It doesn't care what you do with the data only sends it places.
	 * Each raw streaming chunk will be sent to the handleChunk callback
	 * The final result will be merged and returned, 
	 * this should resemble the complete non streamed data
	 * @param callable $handleChunk 
	 * @param array $data 
	 * @return array 
	 * @throws BindingResolutionException 
	 * @throws NotFoundExceptionInterface 
	 * @throws ContainerExceptionInterface 
	 */
	public function makeChatRequest(callable $handleChunk, $data = [])
	{
		$api_url = 'https://api.openai.com/v1/chat/completions';
		$data = array_merge([
			'model' => $this->getChatGptModel(), // or the appropriate model
			'messages' => $this->messages,
			'stream' => true,
			'stream_options' => [
				'include_usage' => true
			]
		], $data);
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . config('services.openai.key')
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$chunks = [];
		curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($handleChunk, &$chunks) {
			static $buffer = '';
			// Append new data to buffer
			$buffer .= $data;
			$errors = json_decode($buffer, true);
			if (isset($errors['error'])) {
				$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				response()->json($errors, $http_status)->send();
				die;
			}
			// Split buffer into chunks of complete JSON responses
			while (($pos = strpos($buffer, "\n")) !== false) {
				$chunk = substr($buffer, 0, $pos);
				$buffer = substr($buffer, $pos + 1);
				if (strlen(trim($chunk)) > 0) {
					$jsonChunk = str_replace('data: ', '', $chunk);
					if ($jsonChunk === '[DONE]') {
						// handle this if necessary
						continue;
					}
					$chunkData = json_decode($jsonChunk, true);
					$chunks[] = $chunkData;
					call_user_func($handleChunk, $chunkData, $chunks);
				}
			}
			
			return strlen($data);
		});

		$result = curl_exec($ch);
		if ($result === false) {
			throw new \Exception('cURL error: ' . curl_error($ch));
		}

		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_status >= 400) {
			$error_info = print_r(curl_getinfo($ch), true);
			throw new \Exception($error_info);
		}

		curl_close($ch);

		return $this->mergeChatChunks($chunks);
	}

	public function mergeChatChunks($chunks)
	{
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
		foreach ($chunks as $item) {
			// Merge common attributes (assuming they are the same for all elements)
			$merged['id'] = Arr::get($item, 'id', '');
			$merged['object'] = Arr::get($item, 'object', '');
			$merged['created'] = Arr::get($item, 'created', '');
			$merged['model'] = Arr::get($item, 'model', '');
			$merged['system_fingerprint'] = Arr::get($item, 'system_fingerprint', '');

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

	public static function processDeltas($chunks)
	{
		$combined = [];
		foreach ($chunks as $chunk) {
			$combined = self::recursiveMergeWithConcat($combined, $chunk);
		}
		return $combined;
	}

	public static function recursiveMergeWithConcat($array1, $array2, $isDelta = false)
	{
		foreach ($array2 as $key => $value) {
			// If the key exists in the first array
			if (isset($array1[$key])) {
				if (is_array($array1[$key]) && is_array($value)) {
					// Both values are arrays, recurse
					$array1[$key] = self::recursiveMergeWithConcat($array1[$key], $value);
				} elseif (is_string($array1[$key]) && is_string($value)) {
					// Both values are strings, concatenate
					if ($isDelta)
						$array1[$key] .= $value;
					else
						$array1[$key] = $value;
				} else {
					// If types don't match or are not both arrays/strings, replace the value
					$array1[$key] = $value;
				}
			} else {
				// Key does not exist in the first array, add it
				$array1[$key] = $value;
			}
		}

		// Handle nested 'delta' keys
		if (isset($array2['delta'])) {
			$array1 = self::recursiveMergeWithConcat($array1, $array2['delta'], true);
			unset($array1['delta']);
		}

		return $array1;
	}
}