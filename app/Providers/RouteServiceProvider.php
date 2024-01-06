<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * The path to your application's "home" route.
	 *
	 * Typically, users are redirected here after authentication.
	 *
	 * @var string
	 */
	public const HOME = '/dashboard';

	protected function mapWebRoutes()
	{
		foreach ($this->centralDomains() as $domain) {
			Route::middleware('web')
				->domain($domain)
				->namespace($this->namespace)
				->group(base_path('routes/web.php'));
		}
	}

	protected function mapApiRoutes()
	{
		foreach ($this->centralDomains() as $domain) {
			Route::prefix('api')
				->middleware('api')
				->domain($domain)
				->namespace($this->namespace)
				->group(base_path('routes/api.php'));
		}
	}

	protected function centralDomains(): array
	{
		return config('tenancy.central_domains');
	}

	protected function configureRateLimiting()
	{
		RateLimiter::for('api', function (Request $request) {
			return Limit::perMinute(500)->by($request->user()?->id ?: $request->ip());
		});
	}

	/**
	 * Define your route model bindings, pattern filters, and other route configuration.
	 */
	public function boot(): void
	{
		$this->configureRateLimiting();

		$this->routes(function () {
			$this->mapApiRoutes();
			$this->mapWebRoutes();
		});
	}
}
