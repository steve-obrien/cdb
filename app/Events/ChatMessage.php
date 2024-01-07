<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatMessage implements ShouldBroadcast
{
	use InteractsWithSockets, SerializesModels;

	public $message;

	public function broadcastAs()
	{
		return 'ChatMessage';
	}

	public function __construct($message)
	{
		$this->message = $message;
	}

	public function broadcastOn()
	{
		return new Channel('chat');
	}
}
