<?php

use Illuminate\Support\Facades\Route;

/**
 * API routes like in laravel;
 */
Route::prefix('api')
	->middleware('api')
	->get(
		'health-check',
		\Wjezowski\LaravelEureka\Http\Controllers\HealthCheck::class . '@healthCheck'
	);
