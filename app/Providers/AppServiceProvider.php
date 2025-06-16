<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GmailService;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		$this->app->singleton(GmailService::class, function ($app) {
			return new GmailService();
		});
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		//
	}
}
