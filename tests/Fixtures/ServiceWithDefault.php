<?php

namespace Tests\Fixtures;

class ServiceWithDefault
{
    public string $name;

    public function __construct(string $name = 'default')
    {
        $this->name = $name;
    }
}
