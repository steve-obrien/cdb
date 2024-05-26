<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id - uuid
 * @property string $created_at - string
 * @property string $role - string
 */
class Chat extends Model
{
	use HasFactory, HasUuids;

	protected $guarded = [];

	protected $casts = [
		'chunks' => 'array',
		'tool_calls' => 'array',
	];

	/**
	 * Get the phone associated with the user.
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
