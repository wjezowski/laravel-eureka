<?php

declare(strict_types=1);

namespace Dirkuu\Eureka\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class HealthCheck extends Controller
{
	public function healthCheck(): Response
	{
		return new Response('OK');
	}
}