<?php

namespace Arc\Framework\Console;

class MakeControllerCommand extends AbstractGeneratorCommand
{
    public function getName(): string
    {
        return "make:controller";
    }

    public function getDescription(): string
    {
        return "Create controller";
    }

    public function getStub(): string
    {
        return 'controller.stub';
    }

    public function getDirectory(): string
    {
        return $this->setting('controller', 'dir', 'app/Controllers');
    }

    protected function getNamespace(): string
    {
        return $this->setting('controller', 'namespace', 'App\\Controllers');
    }
}
