<?php

namespace App\Models;

use App\Models\Traits\Versionable;
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
 * @property string $parent_id
 * 
 * @package App\Models
 */
class UiComponent extends Model
{
	use HasUlids, HasFactory, Versionable;

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
		'prompt', 'user_id', 'html', 'parent_id'
	];

	public function getPrevious()
	{
		return self::where('parent_id', $this->parent_id)
			->where('created_at', '<', $this->created_at)
			->orderBy('created_at', 'desc')
			->first();
	}

	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void
	{
		static::created(function (UiComponent $component) {
			// ...
		});

		static::updated(function (UiComponent $component) {
			// ...
			// save a version
		});
	}
}
