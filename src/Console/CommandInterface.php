<?php

namespace Arc\Framework\Console;

use Arc\Framework\Console\Input;

interface CommandInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function execute(Input $input): int;
}
