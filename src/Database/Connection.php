<?php

require_once 'QueryBuilder.php';


class Connection
{
	private PDO $pdo;

	public function __construct(string $dsn, string $user, string $password) {
		$this->pdo = new PDO($dsn, $user, $password, [
    	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);

		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
	}

	public function beginTransaction(): void {
		$this->pdo->beginTransaction();
	}

	public function commit(): void {
		$this->pdo->commit();
	}
	
	public function rollback(): void {
		$this->pdo->rollBack();
	}

	private function prepareAndExecute(string $sql, array $params): PDOStatement {
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		
		return $stmt;
	}

	public function table(string $table): QueryBuilder {
		return (new QueryBuilder($this))->table($table);
	}


	public function fetch(string $sql, array $params = []): ?array {
		$stmt = $this->prepareAndExecute($sql, $params);
		$result = $stmt->fetch();

		return $result === false ? null : $result;
	}

	public function fetchAll(string $sql, array $params = []): array {
		$stmt = $this->prepareAndExecute($sql, $params);
		$result = $stmt->fetchAll();

		return $result;
	}

	public function execute(string $sql, array $params = []): int {
		$stmt = $this->prepareAndExecute($sql, $params);

		return $stmt->rowCount();
	}

	public function insert(string $sql, array $params = []): string|int {
		$this->prepareAndExecute($sql, $params);

		return $this->pdo->lastInsertId();
	}

}