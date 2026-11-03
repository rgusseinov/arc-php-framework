<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;
use Arc\Framework\Middlewares\LoggingMiddleware;
use Arc\Framework\Middlewares\MiddlewarePipeline;
use Arc\Framework\Middlewares\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class HttpKernel implements RequestHandlerInterface
{
    private Router $router;
    private Container $container;

    public function __construct(Container $container)
    {
        $this->router = $container->get(Router::class);
        $this->container = $container;
    }

    public function handle(Request $request): Response
    {
        $pipeline = new MiddlewarePipeline([
          new LoggingMiddleware()
        ]);

        try {
            $finalHandler = new FinalHandler($this->router, $this->container);
            $response = $pipeline->handle($request, $finalHandler);

            return $response;
        } catch (Throwable $e) {
            $logger = $this->container->get(LoggerInterface::class);
            $logger->critical($e->getMessage());

            return new Response(500, [], 'Internal Server Error');
        }

    }
}
