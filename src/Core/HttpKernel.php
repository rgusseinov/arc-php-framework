<?php

// HttpKernel — это оркестратор HTTP lifecycle: Request → Routing → Controller → Response.

interface RequestHandlerInterface {
  public function handle(Request $request): Response;
}

class FinalHandler implements RequestHandlerInterface {
  private $router;
  private $container;
  
  public function __construct($router, $container)
  {
    $this->router = $router; 
    $this->container = $container; 
  }

  public function handle(Request $request): Response {
    $route = $this->router->match($request);

    if ($route === null) {
      return new Response(404, [], "Error 404!");
    }

    $handler = $route->getHandler();
    $routeParams = $route->getParams();
    
    // Controller handler: [Controller::class, 'method']
    if (is_array($handler) && count($handler) === 2) {
      
      [$controllerClass, $method] = $handler;
      
      $controller = $this->container->get($controllerClass);

      if (!method_exists($controller, $method)) {
          return new Response(404, [], "Method {$method} not exists");
      }

      $reflection = new ReflectionMethod($controller, $method);
      $parameters = $reflection->getParameters();
      
      $args = [];

      foreach ($parameters as $param) {

          $name = $param->getName();
          $type = $param->getType();

          // 1️⃣ route params
          if (array_key_exists($name, $routeParams)) {
              $args[] = $routeParams[$name];
              continue;
          }

          // 2️⃣ Request
          if ($type && $type->getName() === Request::class) {
              $args[] = $request;
              continue;
          }

          // 3️⃣ Container service
          if ($type && !$type->isBuiltin()) {
              $args[] = $this->container->get($type->getName());
              continue;
          }

          // 4️⃣ fallback
          return new Response(500, [], "The Argument can't be solve");
      }

    
      $response = $reflection->invokeArgs($controller, $args);
    }
    // Closure handler
    elseif ($handler instanceof Closure) {
        $response = $handler($request);
    }
    // Unknown handler type
    else {
        return new Response(500, [], "Invalid route handler");
    }

    // Гарантия контракта: всегда возвращаем Response
    if (!$response instanceof Response) {
        return new Response(500, [], "Handler must return Response instance");
    }

    return $response;
  }
}


class HttpKernel implements RequestHandlerInterface {
  private $router;
  private $container;

  public function __construct($router, $container){
    $this->router = $router; 
    $this->container = $container; 
  }

  public function handle(Request $request): Response {
    $pipeline = new MiddlewarePipeline([
      new LoggingMiddleware()
    ]);

    $finalHandler = new FinalHandler($this->router, $this->container);
    $response = $pipeline->handle($request, $finalHandler);

    return $response;
  }
}
