<?php

namespace Arc\Framework\Console;

use Arc\Framework\Application\Application;
use Exception;

class ServeCommand implements CommandInterface
{
    private int $defaultPort = 8000;
    private string $defaultHost = '127.0.0.1';
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getName(): string
    {
        return "serve";
    }

    public function getDescription(): string
    {
        return "Start the Arc development server";
    }

    public function execute(Input $input): int
    {
        $port = $input->getOption('port', $this->defaultPort);
        $host = $input->getOption('host', $this->defaultHost);

        if (filter_var($port, FILTER_VALIDATE_INT) === false) {
            throw new Exception("Port {$port} must be an integer");
        }

        $port = (int)$port;

        if (!$this->validatePort($port)) {
            throw new Exception("The port {$port} is out range 1..65535");
        }

        if (!is_string($host) || $host === '') {
            throw new Exception("Host must be a non-empty string");
        }

        $command = $this->buildCommandString($host, $port);

        system($command, $retval);

        return $retval;
    }

    private function validatePort(int $port): bool
    {
        return ($port >= 1 && $port <= 65535);
    }

    private function buildCommandString(string $host, int $port): string
    {
        return sprintf(
            'php -S %s:%d -t %s',
            escapeshellarg($host),
            $port,
            escapeshellarg($this->app->basePath('public'))
        );
    }
}
