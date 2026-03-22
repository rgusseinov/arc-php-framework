<?php

class HomeController {
  public function index(Request $request): Response {
    return new Response(200, [], 'Main body text = ' . $request->getBody());
  }

  public function show(Request $request): Response {
    return new Response(200, [], 'Body text = ' . $request->getBody());
  }
}