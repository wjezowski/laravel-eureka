<?php

declare(strict_types=1);

namespace wjezowski\LaravelEureka;

use Illuminate\Support\ServiceProvider;
use wjezowski\LaravelEureka\Console\Commands\EurekaHeartbeatCommand;

class LaravelEurekaProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

		if ($this->app->runningInConsole()) {
			$this->commands([
				EurekaHeartbeatCommand::class
            ]);
		}
	}
}
