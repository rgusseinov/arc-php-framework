<?php

namespace Tests\Fixtures;

class ServiceWithDependency
{
    public SimpleService $simple;

    public function __construct(SimpleService $simple)
    {
        $this->simple = $simple;
    }
}
