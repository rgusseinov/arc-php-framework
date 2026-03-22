<?php

interface RouterInterface {
  public function get(string $path, $handler);
  public function match(Request $request): callable|array|Route|null;
}

class Router implements RouterInterface {
  private array $routes = [
    'GET' => [],
    'POST' => [],
  ];

  /*
    [
      'GET' => [
        '/' => callable,
        '/users' => callable,
        '/users/{id}' => callable,
      ],
      'POST' => [
        '/users' => callable,
      ],
    ]
  */


  /* [
    'GET' => [
          Route,
          Route,
          Route
    ]
  ] */


  private function addRoutes(string $method, string $path, callable|array $handler) {
    $method = strtoupper($method);
    $routes = $this->routes[$method] ?? [];

    foreach ($routes as $route){
      if ($route->getPath() === $path){
        throw new RuntimeException("Route [$method /$path] already exists");
      }
    }

    $routes[] = new Route($method, $path, $handler);
    
    $this->routes[$method] = $routes;
  }

  public function get(string $path, $handler)
  {
    $this->addRoutes('GET', $path, $handler);
  }

  /* 
  $router->get('/', [HomeController::class, 'index']);
  $router->get('/tasks', [HomeController::class, 'tasks']);

  $router->get('/users/{id}', [HomeController::class, 'index']);
  */

  public function match(Request $request): callable|array|Route|null
  {
    $method = strtoupper($request->getMethod());
    $path   = $request->getPath();

    $routes = $this->routes[$method] ?? [];

    $userPathSegments = array_values(array_filter(explode('/', $path)));
      // echo '<pre>'; print_r($routes); exit;

    foreach ($routes as $route){
      if ($route->getPath() === $path){
        $route->setParams([]);

        return $route;
      }

      $routePath = $route->getPath();

      $routeSegments = array_values(array_filter(explode('/', $routePath)));

      // количество сегментов должно совпадать
      if (count($routeSegments) !== count($userPathSegments)) {
          continue;
      }

      $params = [];
      $matched = true;

      /*
        /users/{id}
        /users/42
      */

      foreach ($routeSegments as $index => $routeSegment) {

          // {id}, {anything} — wildcard
          if (
              str_starts_with($routeSegment, '{') &&
              str_ends_with($routeSegment, '}')
          ) {
            
            $paramName = substr($routeSegment, 1, strlen($routeSegment) - 2);

            $params[$paramName] = $userPathSegments[$index];

            continue;
          }

          // обычное сравнение сегментов
          if ($routeSegment !== $userPathSegments[$index]) {
              $matched = false;
              break;
          }
      }

      if ($matched) {
        // print_r($params); exit;
        $route->setParams($params);

        return $route;
      }

    }
    return null;
  }
}