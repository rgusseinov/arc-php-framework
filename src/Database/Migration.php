<?php

namespace Arc\Framework\Database;

abstract class Migration
{
    protected Connection $connection;

    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    abstract public function up(): void;

    abstract public function down(): void;
}
