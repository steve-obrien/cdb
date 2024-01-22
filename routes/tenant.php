<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatApiController;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
	'web',
	InitializeTenancyByDomain::class,
	PreventAccessFromCentralDomains::class,
])->group(function () {
	Route::get('/', function () {
		return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
	});

	Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
	// Duplicates here confuse the Ziggy url generation client side
	// Route::get('/chat', [ChatController::class, 'chat'])->name('chat');
	// Route::get('/chat/{id}', [ChatController::class, 'chatSession'])->name('chat.session');

	// Route::match(['get', 'post'], 'v1/chat/', [ChatApiController::class, 'chat'])->name('api.chat'); // to deprecate
	
	// Route::match(['post'], 'v1/chat-start/', [ChatApiController::class, 'chatStart'])->name('api.chatStart');
	// Route::match(['get', 'post'], 'v1/chat-stream/{id}', [ChatApiController::class, 'chatStream'])->name('api.chatStream');

});

// Route::middleware([
// 	'web',
// 	InitializeTenancyByDomain::class,
// 	PreventAccessFromCentralDomains::class,
// ])->group(function () {

// 	Route::get('/', function () {
// 		return Inertia::render('Welcome', [
// 			'canLogin' => Route::has('login'),
// 			'canRegister' => Route::has('register'),
// 			'laravelVersion' => Application::VERSION,
// 			'phpVersion' => PHP_VERSION,
// 		]);
// 	});
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

/*

Route::middleware(['web',
InitializeTenancyByDomain::class,
'auth',
'verified',])->group(function () {
	Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

	Route::get('/chat', [ChatController::class, 'chat'])->name('chat');
	Route::get('/chat/{id}', [ChatController::class, 'chatSession'])->name('chat.session');

	Route::delete('/chat/{id}', [ChatApiController::class, 'chatSessionDelete'])->name('api.chatSessionDelete');
	Route::match(['get', 'post'], 'v1/chat/', [ChatApiController::class, 'chat'])->name('api.chat'); // to deprecate
	Route::match(['post'], 'v1/chat-start/', [ChatApiController::class, 'chatStart'])->name('api.chatStart');
	Route::match(['get', 'post'], 'v1/chat-stream/{id}', [ChatApiController::class, 'chatStream'])->name('api.chatStream');
});

Route::middleware(['auth', InitializeTenancyByDomain::class])->group(function () {
	// Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('guest')->group(function () {
	Route::get('register', [RegisteredUserController::class, 'create'])
		->name('register');

	Route::post('register', [RegisteredUserController::class, 'store']);

	Route::get('login', [AuthenticatedSessionController::class, 'create'])
		->name('login');

	Route::post('login', [AuthenticatedSessionController::class, 'store']);

	Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
		->name('password.request');

	Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
		->name('password.email');

	Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
		->name('password.reset');

	Route::post('reset-password', [NewPasswordController::class, 'store'])
		->name('password.store');
});

*/