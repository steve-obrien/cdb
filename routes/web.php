<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ChatApiController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Pusher\Pusher;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return Inertia::render('Welcome', [
		'canLogin' => Route::has('login'),
		'canRegister' => Route::has('register'),
		'laravelVersion' => Application::VERSION,
		'phpVersion' => PHP_VERSION,
	]);
});

// user avatar image
Route::get('/avatar/{id}', function ($id) {
	$user = User::findOrFail($id);
	return $user->avatarUrl;
})->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
	Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

	Route::get('/chat', [ChatController::class, 'chat'])->name('chat');
	Route::get('/chat/{id}', [ChatController::class, 'chatSession'])->name('chat.session');
	Route::delete('/chat/{id}', [ChatApiController::class, 'chatSessionDelete'])->name('api.chatSessionDelete');
	Route::match(['get', 'post'], 'v1/chat/', [ChatApiController::class, 'chat'])->name('api.chat'); // to deprecate
	Route::match(['post'], 'v1/chat-start/', [ChatApiController::class, 'chatStart'])->name('api.chatStart');
	Route::match(['get', 'post'], 'v1/chat-stream/{id}', [ChatApiController::class, 'chatStream'])->name('api.chatStream');

	Route::get('/team', [TeamController::class, 'team'])->name('team');
	Route::post('/team/invite', [TeamController::class, 'invite'])->name('team.invite');
});

Route::get('/team/invite/accept/{token}', [TeamController::class, 'inviteAccept'])->name('team.invite.accept');

Route::middleware('auth')->group(function () {
	// Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

	Route::delete('/profile/other-browser-sessions', [ProfileController::class, 'destroyOtherBrowserSessions'])
		->name('profile.destroyOtherBrowserSessions');
});


Route::get('/event', function (Request $request) {

	$pusher = new Pusher(
		config('broadcasting.connections.pusher.key', []),
		config('broadcasting.connections.pusher.secret', []),
		config('broadcasting.connections.pusher.app_id', []),
		config('broadcasting.connections.pusher.options', []),
	);
	$broadcaster = new PusherBroadcaster($pusher);

	$broadcaster->broadcast(
		['chat'],
		'App\Events\ChatMessage',
		json_decode('{"message": "hello"}', true)
	);

	return 'ok';
});




// Route::get('/auth/redirect', function () {
// 	return Socialite::driver('github')->redirect();
// });

// Route::get('/auth/callback', function () {
// 	$githubUser = Socialite::driver('github')->user();

// 	$user = User::updateOrCreate([
// 		'github_id' => $githubUser->id,
// 	], [
// 		'name' => $githubUser->name,
// 		'email' => $githubUser->email,
// 		'github_token' => $githubUser->token,
// 		'github_refresh_token' => $githubUser->refreshToken,
// 	]);

// 	Auth::login($user);

// 	return redirect('/dashboard');
// });

require __DIR__ . '/auth.php';