<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

use Arc\Framework\Http\Request;
use RuntimeException;

class Router implements RouterInterface {
  /** @var array<string, list<Route>> */
  private array $routes = [
    'GET' => [],
    'POST' => [],
  ];

  /**
   * @param array<int, mixed> $handler
   */
  private function addRoutes(string $method, string $path, array $handler): void {
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

  /**
   * @param array<int, mixed> $handler
   */
  public function get(string $path, array $handler): void
  {
    $this->addRoutes('GET', $path, $handler);
  }

  public function match(Request $request): ?Route
  {
    $method = strtoupper($request->getMethod());
    $path   = $request->getPath();

    $routes = $this->routes[$method] ?? [];

    $userPathSegments = array_values(array_filter(explode('/', $path)));

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
        $route->setParams($params);

        return $route;
      }
    }

    return null;
  }
}