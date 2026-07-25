<?php

interface MiddlewareInterface {
  public function process(Request $request, RequestHandlerInterface $handler): Response;
}