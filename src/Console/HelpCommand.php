<?php

class HelpCommand implements CommandInterface {
	private array $commands;

	public function setCommands(array $commands){
		$this->commands = $commands;
	}

	public function getName(): string {
		return "help";
	}

	public function getDescription(): string {
		return "Display help information";
	}

	public function execute(Input $input): int {
		$commands = $this->commands;

		$info = "Arc CLI\n\n";
		$info .= "Available commands\n\n";

		foreach ($commands as $command){
			$name = str_pad($command->getName(), 10);
			$description = $command->getDescription();

			$info .= "  {$name}{$description}" . PHP_EOL;
		}

		echo $info;
		
		return 0;
	}
}