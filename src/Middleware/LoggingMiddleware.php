<?php

class LoggingMiddleware implements MiddlewareInterface {
    public function process(Request $request, RequestHandlerInterface $handler): Response {
        echo "LogMiddleware: before\n";
        
        $response = $handler->handle($request);
        
        echo "LogMiddleware: after\n";

        return $response;
    }
}
