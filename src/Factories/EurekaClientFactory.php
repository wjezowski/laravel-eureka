<?php

declare(strict_types=1);

namespace Dirkuu\Eureka\Factories;

use Eureka\EurekaClient;

abstract class EurekaClientFactory
{
	public static function create(): EurekaClient
	{
		$homePageUrl = env('SERVICE_IP') . ':' . env('SERVICE_PORT');

		return new EurekaClient([
	        'eurekaDefaultUrl' => env('EUREKA_SERVICE_URL'),
	        'hostName' => env('SERVICE_IP'),
	        'appName' => env('SERVICE_IP'),
	        'ip' => env('SERVICE_IP'),
	        'port' => [env('SERVICE_PORT'), true],
	        'homePageUrl' => $homePageUrl,
	        'healthCheckUrl' => "$homePageUrl/api/health-check"
        ]);
	}
}