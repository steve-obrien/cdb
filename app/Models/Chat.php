<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsStringable;

/**
 * @property string $id - uuid
 */
class Chat extends Model
{
	use HasFactory, HasUuids;

	protected $guarded = [];

	protected $casts = [
		'chunks' => 'array',
	];

}
