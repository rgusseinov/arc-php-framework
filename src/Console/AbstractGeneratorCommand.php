<?php

namespace Arc\Framework\Console;

use Arc\Framework\Application\Application;
use Arc\Framework\Console\Filesystem\Filesystem;

abstract class AbstractGeneratorCommand implements CommandInterface
{
    protected Application $app;

    abstract protected function getDirectory(): string;
    abstract protected function getStub(): string;

    public function __construct(Application $app)
    {
        $this->app = $app;
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

        $template = '{{class}}';

        $replacement = str_replace($template, $argument, $content);

        $filesystem->write($file, $replacement);

        echo "{$argument} created successfully. " . PHP_EOL;

        return 0;
    }

    protected function getFileName(string $argument): string
    {
        return $argument . '.php';
    }
}
