<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 * @property int $invited_by
 * @property string $token
 */
class Invitation extends Model
{
	use HasFactory;

	protected $fillable = ['name', 'email', 'token', 'invited_by'];

	public function inviter()
	{
		return $this->belongsTo(User::class, 'invited_by');
	}
}
