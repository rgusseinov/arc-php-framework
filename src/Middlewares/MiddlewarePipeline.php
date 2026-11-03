<?php

namespace Arc\Framework\Middlewares;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;

class MiddlewarePipeline {
  /** @var list<MiddlewareInterface> */
  private array $middlewares = [];

  /**
   * @param list<MiddlewareInterface> $middlewares
   */
  public function __construct(array $middlewares){
    $this->middlewares = $middlewares;
  }

  public function handle(Request $request, RequestHandlerInterface $next): Response {

    foreach (array_reverse($this->middlewares) as $middleware){
      $next = new MiddlewareHandler($middleware, $next);
    }
      
    return $next->handle($request);
  }
}