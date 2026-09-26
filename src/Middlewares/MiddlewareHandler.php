<?php

namespace Arc\Framework\Middlewares;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;

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