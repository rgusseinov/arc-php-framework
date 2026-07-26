<?php

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
