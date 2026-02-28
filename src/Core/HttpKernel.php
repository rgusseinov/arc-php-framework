<?php

// HttpKernel — это оркестратор HTTP lifecycle: Request → Routing → Controller → Response.

class HttpKernel {
  private $router;
  private $container;

  public function __construct($router, $container){
    $this->router = $router; 
    $this->container = $container; 
  }

  public function handle(Request $request){
    
    $handler = $this->router->match($request);

    if ($handler == null){
      return new Response(404, [], "Error 404!");
    }

    return $handler($request);

    // return HttpResponse
  }
}