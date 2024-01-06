<?php

use App\Models\User;
use Illuminate\Support\Facades\Hasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Psr\Container\ContainerInterface;
use Illuminate\Support\Facades\Hash;


/**
 * **************************************************************************
 * API Routes
 * **************************************************************************
 * 
 * Note these are prefixed with /api/ 
 * @see \App\Providers\RouteServiceProvider::mapApiRoutes();
 *
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});


/**
 * Gets user name and password of a user.
 * on successful credentials returns a token for api access.
 */
Route::get('/login', function (Request $request) {
	
	$request->expectsJson();
	
	$validator = Validator::make($request->all(), [
		'email' => ['required', 'email'],
		'password' => ['required'],
	]);
	
	if ($validator->fails()) {
		return response()->json([
			'success' => false,
			'errors' => $validator->errors()
		], 422); // 422 Unprocessable Entity
	}

	$creds = $validator->validated();

	/** @var \App\Models\User $user */
	// The Auth::once - is basically doing this:
	// $user = User::query()->where(['email'=>$creds['email']])->first();
	// Hash::check($creds['password'], $user->getAuthPassword())

	// can add an ['active' => 1] - better if this is defined at a lower level.
	if (!Auth::once(['email' => $creds['email'], 'password' => $creds['password']])) {
		// Authentication was successful...
		return response()->json([
			'success' => 'false',
			'errors' => 'Incorrect credentials'
		]);
	}

	// if authenticated generate token:
	$token = $request->user()->createToken('login');
	
	return ['token' => $token->plainTextToken];
});
