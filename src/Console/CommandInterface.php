<?php

interface CommandInterface {
	public function getName(): string;

	public function getDescription(): string;

	public function execute(Input $input): int;
}