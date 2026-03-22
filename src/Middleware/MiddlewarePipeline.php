<?php

class MiddlewarePipeline {
  private array $middlewares = [];

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

class MiddlewareHandler implements RequestHandlerInterface {
  private MiddlewareInterface $middleware;
  private RequestHandlerInterface $handler;

  public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $handler){
    $this->middleware = $middleware;
    $this->handler = $handler;
  }


  public function handle(Request $request): Response {
    return $this->middleware->process($request, $this->handler);
  }
}