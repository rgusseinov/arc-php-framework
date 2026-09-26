<?php

namespace Arc\Framework\Console;

use Arc\Framework\Console\AbstractGeneratorCommand;
use Arc\Framework\Console\CommandInterface;

class MakeModelCommand extends AbstractGeneratorCommand implements CommandInterface {
	public function getName(): string {
		return "make:model";	
	}

	public function getDescription(): string {
		return "Create model";
	}

	public function getStub(): string{
		return 'model.stub';
	}

	public function getDirectory(): string{
		return 'src/Models';
	}
}
