<?php

declare(strict_types=1);

namespace Wjezowski\LaravelEureka\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wjezowski\LaravelEureka\Exceptions\ServiceUnavailableException;

class ServiceUnavailableExceptionTest extends TestCase
{
	public function test_service_unavailable_exception_message(): void
	{
		$testServiceName = 'TestServiceName';
		$serviceUnavailableException = new ServiceUnavailableException($testServiceName);

		$expectedMessage = "Service '$testServiceName' are unavailable";

		$this->assertEquals($expectedMessage, $serviceUnavailableException->getMessage());
		$this->assertEquals(0, $serviceUnavailableException->getCode());
	}
}