<?php

declare(strict_types=1);

namespace Dirkuu\LaravelEureka\Console\Commands;

use Dirkuu\LaravelEureka\Console\Commands\Abstracts\AbstractCommand;
use Dirkuu\LaravelEureka\Factories\EurekaClientFactory;
use Eureka\EurekaClient;
use Eureka\Exceptions\DeRegisterFailureException;

class EurekaHeartbeatCommand extends AbstractCommand
{
	protected const WAITING_TIME = 1;

	protected $signature = 'eureka:run-heartbeat';

	private EurekaClient $client;

	public function __construct()
	{
		parent::__construct();

		$this->client = EurekaClientFactory::create();
	}

	public function __invoke(): void
	{
		try {
			$this->client->start();
		} catch (\Exception) {
			echo 'Eureka is not available. Waiting ' . self::WAITING_TIME . ' second.' . PHP_EOL;

			sleep(self::WAITING_TIME);

			$this->__invoke();
		}
	}

	public function shutdown(): void
	{
		if (!$this->client->isRegistered()) {
			die;
		}

		try {
			$this->client->deRegister();
		} catch (DeRegisterFailureException $exception) {
			$this->error('Service was unable to deregister. Exception message: ' . $exception->getMessage());
		}

		die;
	}
}