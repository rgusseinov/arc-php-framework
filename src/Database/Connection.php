<?php

namespace Arc\Framework\Database;

use PDO;
use PDOStatement;

class Connection
{
    private ?PDO $pdo = null;

    /** @var callable(): PDO */
    private $factory;

    /**
     * @param callable(): PDO $factory
     */
    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    private function pdo(): PDO
    {
        return $this->pdo ??= ($this->factory)();
    }

    public function beginTransaction(): void
    {
        $this->pdo()->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo()->commit();
    }

    public function rollback(): void
    {
        $this->pdo()->rollBack();
    }

    /**
     * @param array<int, mixed> $params
     */
    private function prepareAndExecute(string $sql, array $params): PDOStatement
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function table(string $table): QueryBuilder
    {
        return (new QueryBuilder($this))->table($table);
    }

    /**
     * @param array<int, mixed> $params
     * @return array<string, mixed>|null
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->prepareAndExecute($sql, $params);
        $result = $stmt->fetch();

        return $result === false ? null : $result;
    }

    /**
     * @param array<int, mixed> $params
     * @return list<array<string, mixed>>
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->prepareAndExecute($sql, $params);

        return $stmt->fetchAll();
    }

    /**
     * @param array<int, mixed> $params
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->prepareAndExecute($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * @param array<int, mixed> $params
     */
    public function insert(string $sql, array $params = []): string|int
    {
        $this->prepareAndExecute($sql, $params);

        return $this->pdo()->lastInsertId();
    }
}
