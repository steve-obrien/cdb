<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * @property string $id - uuid
 * @property string $created_at - string
 * @property string $role - string
 */
class File extends Model
{
	use HasFactory, HasUlids;

	protected $guarded = [];

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'filefly_files';

}
