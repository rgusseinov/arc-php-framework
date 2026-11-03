<?php

namespace Tests\Core;

use Arc\Framework\Controllers\HomeController;
use Arc\Framework\Core\Router;
use Arc\Framework\Http\Request;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();

        $this->router = new Router();
        $this->router->get('/tasks', [HomeController::class, 'show']);
    }

    public function testRouteFound(): void
    {
        $request = new Request('GET', '/tasks', [], [], '');
        $route = $this->router->match($request);

        $this->assertNotNull($route);
    }

    public function testRouteHandlerIsMatch(): void
    {
        $request = new Request('GET', '/tasks', [], [], '');

        $route = $this->router->match($request);
        $handler = $route->getHandler();

        $this->assertEquals([HomeController::class, 'show'], $handler);
    }

    public function testRouterIsNull(): void
    {
        $request = new Request('GET', '/abc', [], [], '');
        $route = $this->router->match($request);

        $this->assertNull($route);
    }
}
