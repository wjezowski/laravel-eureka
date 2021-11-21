<?php

use Illuminate\Support\Facades\Route;

Route::get('health-check', '\App\Http\Controllers\HealthCheck@healthCheck');
