<?php

namespace Arc\Framework\Console;

use Arc\Framework\Console\AbstractGeneratorCommand;
use Arc\Framework\Console\CommandInterface;

class MakeControllerCommand extends AbstractGeneratorCommand implements CommandInterface
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
        return 'src/Controllers';
    }
}
