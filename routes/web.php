<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatApiController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Pusher\Pusher;
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

Route::get('/dashboard', function () {
	return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware([App\Http\Middleware\Authenticate::class, 'verified'])->group(function () {
	Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

	Route::get('/chat', [ChatController::class, 'chat'])->name('chat');
	Route::get('/chat/{id}', [ChatController::class, 'chatSession'])->name('chat.session');



	Route::delete('/chat/{id}', [ChatApiController::class, 'chatSessionDelete'])->name('api.chatSessionDelete');
	Route::match(['get', 'post'], 'v1/chat/', [ChatApiController::class, 'chat'])->name('api.chat'); // to deprecate
	Route::match(['post'], 'v1/chat-start/', [ChatApiController::class, 'chatStart'])->name('api.chatStart');
	Route::match(['get', 'post'], 'v1/chat-stream/{id}', [ChatApiController::class, 'chatStream'])->name('api.chatStream');
});

Route::middleware('auth')->group(function () {
	// Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::get('/event', function (Request $request) {


	// $validated = $request->validate([
	//     'appId' => ['required', new AppId()],
	//     'key' => 'required',
	//     'secret' => 'required',
	//     'channel' => 'required',
	//     'event' => 'required',
	//     'data' => 'json',
	// ]);

	// dd(env('PUSHER_PORT'));
	
	$pusher = new Pusher(
		'cdbkey',
		'cdbsecret',
		'cdb',
		config('broadcasting.connections.pusher.options', [])
	);
	$broadcaster = new PusherBroadcaster($pusher);

	$broadcaster->broadcast(
		['chat'],
		'App\Events\ChatMessage',
		json_decode('{"message": "hello"}', true)
	);
	return 'ok';
});


require __DIR__ . '/auth.php';
