<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChatMessageChunk implements ShouldBroadcastNow
{
	use InteractsWithSockets, SerializesModels;

	public $message;
	public $contentChunk;
	public $sessionId;
	private $_chat;

	public function __construct($sessionId, Chat $message, $contentChunk)
	{
		$this->_chat = $message;
		$this->message = [
			'id' => $this->_chat->id,
			'role' => $this->_chat->role,
			'created_at' => $this->_chat->created_at,
			// 'content' => $this->_chat->content,
			'user_id' => $this->_chat->user_id,
		];
		$this->contentChunk = $contentChunk;
		$this->sessionId = $sessionId;
	}

	public function broadcastOn()
	{
		return new PresenceChannel('chat.'.$this->sessionId);
	}
}
