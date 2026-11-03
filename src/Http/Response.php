<?php

declare(strict_types=1);

namespace Arc\Framework\Http;

final class Response
{
    private int $status;

    /** @var array<string, string> */
    private array $headers;

    private string $body;

    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        int $status,
        array $headers,
        string $body
    ) {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function send(): void
    {
        http_response_code($this->getStatus());

        foreach ($this->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        echo $this->getBody();
    }

    public function withHeader(string $name, string $value): self
    {
        $newResponse = clone $this;

        $newResponse->headers[$name] = $value;

        return $newResponse;
    }
}
