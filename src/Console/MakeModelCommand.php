<?php

namespace Arc\Framework\Console;

class MakeModelCommand extends AbstractGeneratorCommand
{
    public function getName(): string
    {
        return "make:model";
    }

    public function getDescription(): string
    {
        return "Create model";
    }

    public function getStub(): string
    {
        return 'model.stub';
    }

    public function getDirectory(): string
    {
        return $this->setting('model', 'dir', 'app/Models');
    }

    protected function getNamespace(): string
    {
        return $this->setting('model', 'namespace', 'App\\Models');
    }
}
