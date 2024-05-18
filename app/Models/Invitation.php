<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	use HasFactory;

	protected $fillable = ['name', 'email', 'token', 'invited_by'];

	public function inviter()
	{
		return $this->belongsTo(User::class, 'invited_by');
	}
}
