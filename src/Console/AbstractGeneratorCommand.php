<?php

namespace Arc\Framework\Console;

use Arc\Framework\Application\Application;
use Arc\Framework\Console\Filesystem\Filesystem;
use Arc\Framework\Core\Config;

abstract class AbstractGeneratorCommand implements CommandInterface
{
    protected Application $app;
    protected Config $config;

    abstract protected function getDirectory(): string;
    abstract protected function getStub(): string;

    public function __construct(Application $app, Config $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function execute(Input $input): int
    {
        $argument = $input->getArgument(0);

        if ($argument === null || $argument === '') {
            echo "Class name is required." . PHP_EOL;
            return 1;
        }

        $path = $this->app->basePath($this->getDirectory());
        $stubFileName = $this->getStub();

        $filesystem = new Filesystem();
        if (!$filesystem->exists($path)) {
            $filesystem->createDirectory($path);
        }

        $file = $path . '/' . $this->getFileName($argument);

        $name = ucfirst($argument);

        if ($filesystem->exists($file)) {
            echo "{$name} {$argument} already exists.." . PHP_EOL;
            return 1;
        }

        $content = file_get_contents(__DIR__ . "/../../stubs/{$stubFileName}");

        if ($content === false) {
            echo "Stub not found." . PHP_EOL;
            return 1;
        }

        $replacement = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$argument, $this->getNamespace()],
            $content
        );

        $filesystem->write($file, $replacement);

        echo "{$argument} created successfully. " . PHP_EOL;

        return 0;
    }

    protected function getFileName(string $argument): string
    {
        return $argument . '.php';
    }

    protected function getNamespace(): string
    {
        return '';
    }

    protected function setting(string $type, string $key, string $default): string
    {
        $section = $this->config->get('generators.' . $type);

        if (is_array($section) && isset($section[$key]) && is_string($section[$key])) {
            return $section[$key];
        }

        return $default;
    }
}
