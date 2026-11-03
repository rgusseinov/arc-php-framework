<?php 

declare(strict_types=1);

namespace Arc\Framework\Core;

class Route {
  private string $method;
  private string $path;

  /** @var array<int, mixed> */
  private array $handler;

  /** @var array<string, string> */
  private array $params = [];

  /**
   * @param array<int, mixed> $handler
   */
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

  /**
   * @return array<int, mixed>
   */
  public function getHandler(): array {
    return $this->handler;
  }

  /**
   * @return array<string, string>
   */
  public function getParams(): array {
    return $this->params;
  }

  /**
   * @param array<string, string> $params
   */
  public function setParams(array $params): void {
    $this->params = $params;
  }

}