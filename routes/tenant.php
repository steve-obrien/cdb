<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
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