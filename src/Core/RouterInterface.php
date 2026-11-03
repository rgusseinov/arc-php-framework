<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

use Arc\Framework\Http\Request;

interface RouterInterface {
  /**
   * @param array<int, mixed> $handler
   */
  public function get(string $path, array $handler): void;

  public function match(Request $request): ?Route;
}