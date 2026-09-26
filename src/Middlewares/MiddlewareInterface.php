<?php

namespace Arc\Framework\Middlewares;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;

interface MiddlewareInterface {
  public function process(Request $request, RequestHandlerInterface $handler): Response;
}