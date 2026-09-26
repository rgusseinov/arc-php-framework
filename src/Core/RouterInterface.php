<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

use Arc\Framework\Http\Request;

interface RouterInterface {
  public function get(string $path, $handler);
  public function match(Request $request): callable|array|Route|null;
}
