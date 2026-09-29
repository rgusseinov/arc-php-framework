<?php

namespace Arc\Framework\Controllers;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;
use Psr\Log\LoggerInterface;

use function Arc\Framework\Helpers\view;

class HomeController {
  private LoggerInterface $logger;

  public function __construct(LoggerInterface $logger){
    $this->logger = $logger;
  }
  public function index(Request $request): Response {
    $this->logger->info('Home page loaded...');

    return new Response(200, [], 'Main body text = ' . $request->getBody());
  }

  public function show(Request $request): Response {
    return view('users/show', ['id' => 42]);
  }
}