<?php

declare(strict_types=1);

namespace Wjezowski\LaravelEureka\Factories;

use Eureka\EurekaClient;
use Eureka\Exceptions\InstanceFailureException;
use Wjezowski\LaravelEureka\Exceptions\ServiceUnavailableException;

abstract class EurekaClientFactory
{
	private static EurekaClient $eurekaClient;
	private static array $unavailableServices = [];

	public static function getEurekaClient(): EurekaClient
	{
		if (!isset(self::$eurekaClient)) {
			$homePageUrl = env('SERVICE_IP') . ':' . env('SERVICE_PORT');

			self::$eurekaClient = new EurekaClient([
                'eurekaDefaultUrl' => env('EUREKA_SERVICE_URL'),
                'hostName' => env('SERVICE_IP'),
                'appName' => env('SERVICE_IP'),
                'ip' => env('SERVICE_IP'),
                'port' => [env('SERVICE_PORT'), true],
                'homePageUrl' => $homePageUrl,
                'healthCheckUrl' => "$homePageUrl/api/health-check"
            ]);
		}

		return self::$eurekaClient;
	}

	/**
	 * This method was created for using it in requests or another short time execution code.
	 * You probably don't want to do many requests for one service when it's not available,
	 * because it will probably not register.
	 *
	 * If you for some reasons don't want to base on saved unavailable services, just use
	 * @see EurekaClient::fetchInstance()
	 * You probably want to use that method when you write a long time execution code.
	 *
	 * @throws ServiceUnavailableException
	 */
	public static function fetchInstance(string $serviceName): object
	{
		if (in_array($serviceName, self::$unavailableServices)) {
			throw new ServiceUnavailableException($serviceName);
		}

		try {
			return self::getEurekaClient()->fetchInstance($serviceName);
		} catch (InstanceFailureException) {
			self::$unavailableServices[] = $serviceName;

			throw new ServiceUnavailableException($serviceName);
		}
	}
}