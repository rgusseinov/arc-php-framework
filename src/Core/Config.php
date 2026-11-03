<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

class Config
{
    private string $configPath;

    /** @var array<string, array<string, mixed>|null> */
    private array $loaded = [];

    public function __construct(string $configPath)
    {
        $this->configPath = rtrim($configPath, '/');
    }

    public function get(string $path, mixed $default = null): mixed
    {
        $parts = explode('.', $path, 2);
        $name = $parts[0];

        $data = $this->load($name);

        if ($data === null) {
            return $default;
        }

        // get('app') without a key returns the whole file array
        if (!isset($parts[1])) {
            return $data;
        }

        return array_key_exists($parts[1], $data) ? $data[$parts[1]] : $default;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function load(string $name): ?array
    {
        if (!array_key_exists($name, $this->loaded)) {
            $file = $this->configPath . '/' . $name . '.php';

            $this->loaded[$name] = is_file($file) ? require $file : null;
        }

        return $this->loaded[$name];
    }
}
