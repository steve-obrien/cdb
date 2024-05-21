<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

	'github' => [
		'client_id' => env('GITHUB_CLIENT_ID'),
		'client_secret' => env('GITHUB_CLIENT_SECRET'),
		'redirect' => 'http://example.com/callback-url',
	],

	// 'google' => [
	// 	'client_id' => '950384888835-qgbaajtc46q17ctscdb7r8aakatg65pe.apps.googleusercontent.com',
	// 	'client_secret' => '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',
	// 	'redirect' => 'https://team-ai.co/login/google/callback',
	// ],

	'google' => [
		'client_id' => env('GOOGLE_CLIENT_ID'),
		'client_secret' => env('GOOGLE_CLIENT_SECRET'),
		'redirect' => env('GOOGLE_REDIRECT_URI'),
	],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN', 'mg.newicon.net'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.eu.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

	'openai' => [
		'key' => env('OPENAI_KEY')
	]

];
