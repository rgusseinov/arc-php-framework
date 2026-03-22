<?php 

/*
  Route objects

  Route должен хранить

  method
  path
  handler

  Route должен иметь методы

  getMethod()
  getPath()
  getHandler()

*/

class Route {
  private string $method;
  private string $path;
  private array $handler;
  private array $params;

  public function __construct(string $method, string $path, array $handler)
  {
    $this->method = $method;
    $this->path = $path;
    $this->handler = $handler;
  }

  public function getMethod(): string {
    return $this->method;
  }

  public function getPath(): string {
    return $this->path;
  }

  public function getHandler(): callable|array {
    return $this->handler;
  }

  public function getParams(): array {
    return $this->params;
  }

  public function setParams(array $params) {
    $this->params = $params;
  }

}