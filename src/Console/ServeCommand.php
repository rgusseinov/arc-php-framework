<?php

class ServeCommand implements CommandInterface {
	private $defaultPort = 8000;

	public function getName(): string {
		return "serve";	
	}

	public function getDescription(): string {
		return "Start the Arc development server";
	}

	public function execute(Input $input): int {
		$port = $input->getOption('port', $this->defaultPort);

		if (filter_var($port, FILTER_VALIDATE_INT) === false){
			throw new Exception("Port {$port} must be an integer");
		}

		$port = (int)$port;
		
		if (!$this->validatePort($port)){
			throw new Exception("The port {$port} is out range 1..65535");
		}

		$command = $this->buildCommandString($port);

		system($command, $retval);

		return $retval;
	}

	private function validatePort(int $port): bool {
		return ($port >= 1 && $port <= 65535);
	}

	private function buildCommandString($port){
		$command = "php -S 127.0.0.1:{$port} -t public";

		return $command;
	}
}