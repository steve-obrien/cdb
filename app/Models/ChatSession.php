<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;

/**
 * @property string $id - uuid
 */
class ChatSession extends Model
{
	use HasUuids, SoftDeletes, BroadcastsEvents;

	// Fillable Fields
	protected $fillable = [
		'name', 'visibility', 'created_by', 'folder', 'prompt'
	];

	public function addSystemMessage($content = 'You are a helpful assistant'): Chat
	{
		return $this->chats()->create([
			'user_id' => auth()->user()->id,
			'content' => $content,
			'chat_session_id' => $this->id,
			'role' => 'system',
		]);
	}


	/**
	 * Add a chat to the session
	 */
	public function addUserChat(string $content): Chat
	{
		return $this->chats()->create([
			'user_id' => auth()->user()->id,
			'content' => $content,
			'chat_session_id' => $this->id,
			'role' => 'user',
			'name' => auth()->user()->name,
		]);
	}

	// Relationships

	public function chats(): HasMany
	{
		return $this->hasMany(Chat::class, 'chat_session_id');
	}

	// ChatSession belongs to a User
	public function createdBy()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	/**
	 * 
	 */
	public function getChatsToOpenAiFormat()
	{
		$chats = $this->chats()
			->select('role', 'name', 'content')
			->orderBy('created_at', 'asc')
			->get();

		// Transform the collection to remove 'name' attribute if it's null
		$chats->transform(function ($item) {
			// Open AI library will error if the name is in the format "Steve OBrien" as it does not allow spaces
			// in the User message object name property
			if (!Str::of($item->name)->isMatch('/^[a-zA-Z0-9_-]{1,64}$/')) {
				// lets try and make a match
				$item->name = str_replace(' ', '-', $item->name);
			}
			// It also gets upset with empty values
			if (empty($item->name)) unset($item->name);
			return $item;
		});

		return $chats->toArray();
	}

	protected $casts = [
		'folder' => 'array',
	];

	/**
	 * Get the channels that model events should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel|\Illuminate\Database\Eloquent\Model>
	 */
	public function broadcastOn(string $event): array
	{
		return [new PrivateChannel('team')];
	}
}
