<?php

namespace Arc\Framework\Console;

use Arc\Framework\Console\CommandInterface;
use Arc\Framework\Database\MigrationRunner;

class MigrateCommand implements CommandInterface {
	private MigrationRunner $runner;

	public function __construct(MigrationRunner $runner){
		$this->runner = $runner;
	}

	public function getName(): string {
		return "migrate";	
	}

	public function getDescription(): string {
		return "Run migrate command";
	}

	public function execute(Input $input): int {
		$this->runner->run();

		return 0;
	}
}