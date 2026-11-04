<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $_ENV[trim($key)] = trim($value);
        }
    }

    public static function get(string $key): ?string
    {
        $value = getenv($key);

        if ($value !== false) {
            return $value;
        }

        return $_ENV[$key] ?? null;
    }
}
