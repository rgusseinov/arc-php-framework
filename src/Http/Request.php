<?php

declare(strict_types=1);

namespace Arc\Framework\Http;

final class Request {
    private string $method;
    private string $path;
    /** @var array<string, mixed> */
    private array $query;

    /** @var array<string, string> */
    private array $headers;
    private string $body;

  /**
   * @param array<string, mixed> $query
   * @param array<string, string> $headers
   */
  public function __construct(
    string $method,
    string $path,
    array $query,
    array $headers,
    string $body
  )
  {
    $this->method = $method;
    $this->path = $path;
    $this->query = $query;
    $this->headers = $headers;
    $this->body = $body;
  }

  public static function fromGlobals(): self {
    return new self(
      $_SERVER['REQUEST_METHOD'] ?? 'GET',
      isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/',
      $_GET,
      function_exists('getallheaders') ? getallheaders() : [],
      file_get_contents('php://input')
    );
  }

  public function getMethod(): string {
    return $this->method;
  }

  public function getPath(): string {
    return $this->path;
  }

  /**
   * @return array<string, mixed>
   */
  public function getQuery(): array {
    return $this->query;
  }

  /**
   * @return array<string, string>
   */
  public function getHeaders(): array {
    return $this->headers;
  }

  public function getBody(): string {
    return $this->body;
  }

  public function withHeader(string $name, string $value): self {
    $newRequest = clone $this;

    $newRequest->headers[$name] = $value;

    return $newRequest;
  }
}