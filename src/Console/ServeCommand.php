<?php

class ServeCommand implements CommandInterface {
	public function getName(): string {
		return "serve";	
	}

	public function getDescription(): string {
		return "Start the Arc development server";
	}

	public function execute(array $arguments): int {
		echo "Starting the Arc development server..." . PHP_EOL;

		return 0;
	}
}