<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChatMessage implements ShouldBroadcastNow
{
	use InteractsWithSockets, SerializesModels;

	public $message;
	public $sessionId;

	public function __construct($sessionId, Chat $message)
	{
		$this->message = $message;
		$this->sessionId = $sessionId;
	}

	public function broadcastOn()
	{
		return new PresenceChannel('chat.'.$this->sessionId);
	}
}
