<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ApiSessionMiddlewareTest extends TestCase
{
    public function test_api_auth_routes_use_web_middleware_for_session_state(): void
    {
        $routes = app('router')->getRoutes()->getRoutesByMethod()['POST'];

        $authRoute = collect($routes)->first(fn ($route) => $route->uri() === 'api/auth/login');

        $this->assertNotNull($authRoute);
        $this->assertContains('web', $authRoute->middleware());
    }
}
