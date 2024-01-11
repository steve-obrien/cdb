<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PusherTest implements ShouldBroadcastNow
{
	use InteractsWithSockets, SerializesModels;

	public $message;

	public function __construct($message)
	{
		$this->message = $message;
	}

	public function broadcastOn()
	{
		return ['my-channel'];
	}

	public function broadcastAs()
	{
		return 'my-event';
	}
}
