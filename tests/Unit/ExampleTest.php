<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

	public function test_ai_process_deltas() {
		$chunks = [
			["index" => 0,"delta" => ["role" => "assistant","tool_calls" => [["id" => "call_JZdseLc9LIP6LL716KfEgcQG","type" => "function","function" => ["name" => "get_current_weather","arguments" => ""]]]],"finish_reason" => null],
			["index" => 0,"delta" => ["tool_calls" => [["function" => ["arguments" => "{\""]]]],"finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => "location"]]]], "finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => "\":\""]]]], "finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => "B"]]]], "finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => "rist"]]]], "finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => "ol"]]]], "finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => ","]]]], "finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => " UK"]]]], "finish_reason" => null],
			["index" => 0, "delta" => ["tool_calls" => [["function" => ["arguments" => "\"}"]]]], "finish_reason" => null],
			["index" => 0, "delta" => [], "finish_reason" => "tool_calls"]
		];
		
		$expected = [
			"role" => "assistant",
			"tool_calls" => [
				[
					"id" => "call_JZdseLc9LIP6LL716KfEgcQG",
					"type" => "function",
					"function" => [
						"name" => "get_current_weather",
						"arguments" => '{"location":"Bristol, UK"}',
					],
				],
			],
		];
		$ai = new \App\Ai();
		$combined = $ai->processDeltas($chunks);

		$this->assertEquals($expected, $combined);

	}
}
