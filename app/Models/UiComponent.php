<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;


/**
 * @property string $id  - ulid
 * @property string $prompt  - json
 * @property int $user_id
 * @property string $html
 * @property timestamp $created_at
 * @property timestamp $updated_at
 * 
 * @package App\Models
 */
class UiComponent extends Model
{
	use HasUlids, HasFactory;

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'prompt' => 'array',
	];

	// Fillable Fields
	protected $fillable = [
		'prompt', 'user_id', 'html'
	];
}
