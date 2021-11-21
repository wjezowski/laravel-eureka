## Installation

- Add `wjezowski/laravel-eureka` to your composer.json as requirement
- Add to your "providers" array in config/app.php this line:
`Wjezowski\LaravelEureka\LaravelEurekaProvider::class`
- Your environment should provide variables:
- - EUREKA_SERVICE_URL - url used to doing heartbeat
- - SERVICE_IP - app ip
- - SERVICE_PORT - app port
- Run `php artisan eureka:run-heartbeat` in your app. I use supervisord for this.
- That's all! Great job!
