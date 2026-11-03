<?php

namespace Arc\Framework\Middlewares;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}
