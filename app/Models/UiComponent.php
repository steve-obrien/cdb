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

	/**
	 * Get the previous version
	 * @return mixed 
	 */
	public function getPrevious()
	{
		return self::where('parent_id', $this->parent_id)
			->where('parent_id', '!=', null)
			->where('created_at', '<', $this->created_at)
			->orderBy('created_at', 'desc')
			->first();
	}

	/**
	 * Get the latest revision of the row
	 * @return UiComponent 
	 */
	public function getLatestRevision()
	{
		$latestRevision = UiComponent::where('parent_id', '=', $this->id)->get()->last();
		// This might be the first one. (no child versions (this row is not a parent))
		if ($latestRevision == null)
			return $this;
		return $latestRevision;
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
