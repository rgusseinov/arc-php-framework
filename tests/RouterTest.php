<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/Http/Request.php';
require_once __DIR__ .'/../src/Core/Router.php';
require_once __DIR__ .'/../src/Core/Route.php';
require_once __DIR__ .'/../src/Controller/HomeController.php';


/*
Протестировать:
  маршрут регистрируется
  маршрут находится
  несуществующий маршрут → null
*/


final class RouterTest extends TestCase
{

    protected function setUp(): void {
        parent::setUp();
        // Your setup code here
    }

    public function testRouteFound(): void
    {
      $request = new Request('GET', '/tasks', [], [], '');
      $router = new Router();
      $router->get('/tasks', [HomeController::class, 'show']);

      $route = $router->match($request);

      $this->assertNotNull($route);
    }
  
    public function testRouteHandlerIsMatch(): void
    {
      $request = new Request('GET', '/tasks', [], [], '');
      $router = new Router();
      $router->get('/tasks', [HomeController::class, 'show']);

      $route = $router->match($request);
      $handler = $route->getHandler();

      $this->assertEquals([HomeController::class, 'show'], $handler);
    }
  
    public function testRouterIsNull(): void
    {
      $request = new Request('GET', '/abc', [], [], '');
      $router = new Router();
      $router->get('/tasks', [HomeController::class, 'show']);

      $route = $router->match($request);

      $this->assertNull($route);
    }
}
