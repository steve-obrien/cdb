<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail as EmailVerify;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Support\Str;

class User extends Authenticatable // implements EmailVerify
{
	use HasApiTokens, HasFactory, Notifiable; // MustVerifyEmail;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'avatar',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
	];

	/**
     * Get the URL to the user's profile photo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function avatarUrl(): Attribute
    {
        return Attribute::get(function (): string {
            return $this->avatar
                    ? Storage::disk('public')->get($this->avatar)
                    : $this->defaultAvatarUrl();
        });
    }

	/**
	 * Get the default profile photo URL if no profile photo has been uploaded.
	 *
	 * @return string
	 */
	protected function defaultAvatarUrl()
	{
		$name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
			return mb_substr($segment, 0, 1);
		})->join(' '));

		return  'https://www.gravatar.com/avatar/'.md5(Str::lower($this->email)).'?s=200';
		return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
	}

	/**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'avatar_url',
    ];
}
