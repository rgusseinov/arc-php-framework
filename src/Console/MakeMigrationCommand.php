<?php

namespace Arc\Framework\Console;

class MakeMigrationCommand extends AbstractGeneratorCommand {
	public function getName(): string {
		return "make:migration";	
	}

	public function getDescription(): string {
		return "Create migration";
	}

	public function getStub(): string{
		return 'migration.stub';
	}

	public function getDirectory(): string{
		return 'migrations';
	}

	protected function getFileName(string $argument): string {
		return parent::getFileName(gmdate('Ymd_His') . '_' . $argument);
	}
}