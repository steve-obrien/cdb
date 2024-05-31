<?php

namespace App;

use Illuminate\Contracts\Container\BindingResolutionException;
use Http\Discovery\Exception\NotFoundException;
use InvalidArgumentException;
use \OpenAI;
use OpenAI\Client;
use OpenAI\Exceptions\ErrorException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
/**
 * Chat GPT AI object
 * Just a very simple wrapper to group the logic not trying to be fancy here.
 */
class Ai {

	public $messages = [];

	/**
	 * 
	 * @param string $role - 'system' | 'user'
	 * @param string $content  - the prompt or content of the message
	 * @param string|null $name - an optional user name
	 * @return $this - a chainable method
	 */
	public function addMessage(String $role, String $content, $name=null)
	{
		$this->messages[] = ['role' => $role, 'content' => $content];
		return $this;
	}

	/**
	 * Sends the messages to ChatGPT and streams the response
	 * Make sure you have added messages
	 * @throws \BadFunctionCallException - if there are no messages
	 * @param callable|null $callback 
	 * @param array $options https://platform.openai.com/docs/api-reference/chat/create can pass in ['response_format' => ['type' => 'json_object' ] ]
	 * @return \Symfony\Component\HttpFoundation\Response 
	 */
	public function stream(callable $callback = null, array $options = [])
	{
		throw_if(empty($this->messages), \BadFunctionCallException::class, 'No messages to send');

		$client = $this->getAiClient();

		$stream = $client->chat()->createStreamed([
			'model' => $this->getChatGptModel(),
			'messages' => $this->messages,
		]);

		$response = new StreamedResponse();
		$chunks = [];
		$response->setCallback(function () use ($stream, $chunks, $callback) {
			try {
				foreach ($stream as $response) {
					$chunk = $response->choices[0]->toArray();
					$chunks[] = $chunk;
					 // Call the callback function if provided
					if ($callback) {
						call_user_func($callback, $chunk, $chunks);
					}
					// lets update the database - we do this per stream which is a little excessive.
					echo "event: message\n";
					echo "data: " . json_encode($chunk) . "\n\n";
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
		return $response->send();
	}
	
	/**
	 * Get OpenAI client
	 * @return Client 
	 * @throws BindingResolutionException 
	 * @throws NotFoundExceptionInterface 
	 * @throws ContainerExceptionInterface 
	 * @throws NotFoundException 
	 */
	public function getAiClient()
	{
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

	/**
	 * Merges arrays with delta keys
	 * Similar behavior to array merge but only operates on a delta key.
	 * And for array keys within delta will concatenate rather than replace.
	 * This is useful for processing ChatGPT streaming response:
	 */
	public static function processDeltas($chunks)
	{
		$combined = [];
		foreach ($chunks as $chunk) {
			$combined = Ai::recursiveMergeWithConcat($combined, $chunk['delta']);
		}
		return $combined;
	}

	public static function recursiveMergeWithConcat($array1, $array2)
	{
		foreach ($array2 as $key => $value) {
			// If the key exists in the first array
			if (isset($array1[$key])) {
				if (is_array($array1[$key]) && is_array($value)) {
					// Both values are arrays, recurse
					$array1[$key] = Ai::recursiveMergeWithConcat($array1[$key], $value);
				} elseif (is_string($array1[$key]) && is_string($value)) {
					// Both values are strings, concatenate
					$array1[$key] .= $value;
				} else {
					// If types don't match or are not both arrays/strings, replace the value
					$array1[$key] = $value;
				}
			} else {
				// Key does not exist in the first array, add it
				$array1[$key] = $value;
			}
		}
		return $array1;
	}
}