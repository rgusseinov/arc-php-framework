<?php

class AuthMiddleware implements MiddlewareInterface {
    public function process(Request $request, RequestHandlerInterface $handler): Response {
        return $handler($request);
    }
}
