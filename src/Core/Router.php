<?php

interface RouterInterface {
  public function get(string $path, $handler);
  public function match(Request $request): null | callable;
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

  private function addRoutes($method, string $path, callable $handler){
    $routes = $this->routes[$method] ?? [];

    if (!array_key_exists($path, $routes)){
      $routes[$path] = $handler;
    }

    $this->routes[$method] = $routes;
  }

  public function get(string $path, $handler)
  {
    $this->addRoutes('GET', $path, $handler);
  }

  public function match(Request $request): null | callable
  {
      $method = $request->getMethod();
      $path   = $request->getPath();

      if (!array_key_exists($method, $this->routes)) {
        return null;
      }

      $methodData = $this->routes[$method];

      // 1️⃣ Exact match (самый приоритетный)
      if (isset($methodData[$path])) {
          return $methodData[$path];
      }

      return null;
  }
}