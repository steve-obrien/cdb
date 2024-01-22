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
		$this->sessionId = $sessionId;
		$this->message = [
			'id' => $message->id,
			'role' => $message->role,
			'created_at' => $message->created_at,
			'content' => $message->content,
			'user_id' => $message->user_id,
			'user' => [
				'name' => $message->user->name,
				'avatar_url' =>  $message->user->avatar_url,
				'id' =>  $message->user->id,
			]
		];
	}

	public function broadcastOn()
	{
		return new PresenceChannel('chat.'.$this->sessionId);
	}
}
