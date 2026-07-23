<?php

class Input {
		private $arguments = [];
		private $options = [];
		
		public function __construct(array $arguments, array $options){
			$this->arguments = $arguments;
			$this->options = $options;
		}

    public function hasOption(string $name): bool {
			return $this->options[$name] ?? false;
    }

    public function getOption(string $name, mixed $default = null): mixed {
			return $this->options[$name] ?? $default;
    }

    public function getOptions(): array {
			return $this->options;
    }

    public function hasArgument(int $index): bool {
			return array_key_exists($index, $this->arguments);
    }

    public function getArgument(int $index, mixed $default = null): mixed {
			return $this->arguments[$index] ?? $default;
    }

    public function getArguments(): array {
			return $this->arguments;
    }
}