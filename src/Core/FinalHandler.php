<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;
use Arc\Framework\Middlewares\RequestHandlerInterface;
use ReflectionMethod;
use ReflectionNamedType;

class FinalHandler implements RequestHandlerInterface
{
    private Router $router;
    private Container $container;

    public function __construct(Router $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function handle(Request $request): Response
    {
        $route = $this->router->match($request);

        if ($route === null) {
            return new Response(404, [], "Error 404!");
        }

        $handler = $route->getHandler();
        $routeParams = $route->getParams();

        // Controller handler: [Controller::class, 'method']
        if (count($handler) !== 2) {
            return new Response(500, [], "Invalid route handler");
        }

        [$controllerClass, $method] = $handler;

        $controller = $this->container->get($controllerClass);

        if (!method_exists($controller, $method)) {
            return new Response(404, [], "Method {$method} not exists");
        }

        $reflection = new ReflectionMethod($controller, $method);
        $parameters = $reflection->getParameters();

        $args = [];

        foreach ($parameters as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // 1 route params
            if (array_key_exists($name, $routeParams)) {
                $args[] = $routeParams[$name];
                continue;
            }

            if ($type instanceof ReflectionNamedType) {
                // 2 Request
                if ($type->getName() === Request::class) {
                    $args[] = $request;
                    continue;
                }

                // 3 Container service
                if (!$type->isBuiltin()) {
                    $args[] = $this->container->get($type->getName());
                    continue;
                }
            }

            // 4 fallback
            return new Response(500, [], "The Argument can't be solve");
        }

        $response = $reflection->invokeArgs($controller, $args);

        // Гарантия контракта: всегда возвращаем Response
        if (!$response instanceof Response) {
            return new Response(500, [], "Handler must return Response instance");
        }

        return $response;
    }
}
