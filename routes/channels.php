<?php

use \App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
	return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{sessionId}', function(User $user, string $sessionId) {
	return [
		'id' => $user->id,
		'name' => $user->name,
		'avatar_url' => $user->avatar_url
	];
});


/**
 * public channel for all
 */
// Broadcast::channel('chat', function() {
// 	return true;
// });


/**
 * Share data with everyone on the team
 * A prescence chanel
 */
Broadcast::channel('team', function(User $user) {
	return true;
});
