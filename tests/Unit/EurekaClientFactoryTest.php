<?php

declare(strict_types=1);

namespace Wjezowski\LaravelEureka\Tests\Unit;

use Eureka\EurekaClient;
use Eureka\Exceptions\InstanceFailureException;
use PHPUnit\Framework\TestCase;
use Wjezowski\LaravelEureka\Exceptions\ServiceUnavailableException;
use Wjezowski\LaravelEureka\Factories\EurekaClientFactory;

class EurekaClientFactoryTest extends TestCase
{
	private const SERVICE_NAME = 'TestService';

	public function test_get_eureka_client(): void
	{
		$eurekaClientProperty = new \ReflectionProperty(EurekaClientFactory::class, 'eurekaClient');
		$eurekaClientProperty->setAccessible(true);

		$this->expectError();
		$this->expectErrorMessage('Typed static property Wjezowski\LaravelEureka\Factories\EurekaClientFactory::$eurekaClient must not be accessed before initialization');

		$this->assertNull($eurekaClientProperty->getValue());
		$this->assertInstanceOf(EurekaClient::class, EurekaClientFactory::getEurekaClient());

		$eurekaClientProperty->setAccessible(false);
	}

	public function test_double_calls_get_eureka_client(): void
	{
		$eurekaClientProperty = new \ReflectionProperty(EurekaClientFactory::class, 'eurekaClient');
		$eurekaClientProperty->setAccessible(true);

		$this->assertInstanceOf(EurekaClient::class, EurekaClientFactory::getEurekaClient());

		$this->assertInstanceOf(EurekaClient::class, $eurekaClientProperty->getValue());
		$this->assertInstanceOf(EurekaClient::class, EurekaClientFactory::getEurekaClient());

		$eurekaClientProperty->setAccessible(false);
	}

	public function test_fetch_instance(): void
	{
		$eurekaClientMock = $this->getMockBuilder(EurekaClient::class)
			->setConstructorArgs([[]])
			->getMock();

		$eurekaClientMock->method('fetchInstance')
			->willReturn($this->getStdClassResponseMock());

		$this->setStaticProperty('eurekaClient', $eurekaClientMock);

		$result = EurekaClientFactory::fetchInstance(self::SERVICE_NAME);

		$this->assertInstanceOf(\stdClass::class, $result);
		$this->assertObjectHasAttribute('instanceId', $result);
	}

	public function test_fetch_instance_instance_failure_exception(): void
	{
		$eurekaClientMock = $this->getMockBuilder(EurekaClient::class)
			->setConstructorArgs([[]])
			->getMock();

		$eurekaClientMock->method('fetchInstance')
			->willThrowException(new InstanceFailureException());

		$this->setStaticProperty('eurekaClient', $eurekaClientMock);

		$this->expectException(ServiceUnavailableException::class);

		EurekaClientFactory::fetchInstance(self::SERVICE_NAME);
	}

	public function test_fetch_instance_service_unavailable_exception(): void
	{
		$this->setStaticProperty('unavailableServices', [self::SERVICE_NAME]);

		$this->expectException(ServiceUnavailableException::class);

		EurekaClientFactory::fetchInstance(self::SERVICE_NAME);
	}

	private function getStdClassResponseMock(): object
	{
		$responseMock = new \stdClass();
		$responseMock->instanceId = "testing:testing:80";

		return $responseMock;
	}

	private function setStaticProperty(string $propertyName, mixed $value): void
	{
		$reflectionProperty = new \ReflectionProperty(
			EurekaClientFactory::class,
			$propertyName
		);

		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue('', $value);
		$reflectionProperty->setAccessible(false);
	}
}