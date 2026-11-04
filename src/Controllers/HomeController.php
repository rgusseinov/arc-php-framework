<?php

namespace App\Controllers;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;

use function Arc\Framework\Helpers\view;

class HomeController
{
    public function index(Request $request): Response
    {
        return new Response(200, [], $request->getBody());
    }

    public function show(Request $request): Response
    {
        return view('users/show', ['id' => 42]);
    }
}
