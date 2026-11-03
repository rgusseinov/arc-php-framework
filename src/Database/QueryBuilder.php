<?php

namespace Arc\Framework\Database;

use InvalidArgumentException;

class QueryBuilder {
  private Connection $connection;
  private string $table;

  /** @var array<int, string> */
  private array $columns = ["*"];

  /**
   * @var list<array{type: string, data: array{column: string, operator: string|null, value: mixed}}>
   */
  private array $wheres = [];

  public function __construct(Connection $connection){
    $this->connection = $connection;
  }

  public function table(string $table): self {
    $this->table = $table;

    return $this;
  }

  /**
   * @param array<int, string>|string $columns
   */
  public function select(array|string $columns): self {
    $this->columns = is_array($columns) ? $columns : [$columns];

    return $this;
  }

  public function where(string $column, string $operator, mixed $value): self {
    $this->wheres[] = [
      'type' => 'basic',
      'data' => [
        'column' => $column,
        'operator' => $operator,
        'value' => $value
      ]
    ];

    return $this;
  }

  /**
   * @return array{sql: string, params: array<int, mixed>}
   */
  private function buildSelect(): array {
      $table = $this->table;
      $columns = $this->columns;

      $columnsString = implode(',', $columns);
      $sql = "SELECT {$columnsString} FROM {$table}";

      ['sql' => $conditionString, 'params' => $params] = $this->buildWhere();

      if (!(empty($conditionString))){
        $sql .= " WHERE {$conditionString}";
      }

      return ['sql' => $sql, 'params' => $params];
  }

  /**
   * @return array<string, mixed>|null
   */
  public function first(): ?array {
    ['sql' => $sql, 'params' => $params] = $this->buildSelect();

    return $this->connection->fetch($sql, $params);
  }

  /**
   * @return list<array<string, mixed>>
   */
  public function get(): array {
    ['sql' => $sql, 'params' => $params] = $this->buildSelect();

    return $this->connection->fetchAll($sql, $params);
  }

  /**
   * @param array<string, mixed> $data
   * @return array{sql: string, params: array<int, mixed>}
   */
  private function buildInsert(array $data): array {
    $table = $this->table;

    $fields = implode(',', array_keys($data));
    $params = array_values($data);
    $placeholders = implode(',', array_fill(0, count($params), '?'));

    $sql = "INSERT INTO {$table} ($fields) VALUES ($placeholders)";

    return ['sql' => $sql, 'params' => $params];
  }

  /**
   * @param array<string, mixed> $data
   */
  public function insert(array $data): string|int {
    if (empty($data)){
      throw new InvalidArgumentException('Inserted data can"t be empty');
    }

    ['sql' => $sql, 'params' => $params] = $this->buildInsert($data);

    return $this->connection->insert($sql, $params);
  }

  /**
   * @param array<string, mixed> $data
   * @return array{sql: string, params: array<int, mixed>}
   */
  private function buildUpdate(array $data): array {
    $wheres = $this->wheres;

    if (empty($wheres)){
      throw new InvalidArgumentException('Where condition must me specified');
    }

    $table = $this->table;

    $clauses = [];
    $setParams = [];

    foreach (array_keys($data) as $key){
      $clauses[] = "{$key} = ?";

      $setParams[] = $data[$key];
    }

    ['sql' => $whereConditionString, 'params' => $params] = $this->buildWhere();

    $params = array_merge($setParams, $params);

    $clausesString = implode(',', $clauses);
    $sql = "UPDATE {$table} SET {$clausesString} WHERE {$whereConditionString}";

    return ['sql' => $sql, 'params' => $params];
  }

  /**
   * @return array{sql: string, params: array<int, mixed>}
   */
  private function buildWhere(): array {
    $wheres = $this->wheres;

    $condition = [];
    $params = [];

    foreach ($wheres as $where){
      $type = $where['type'];
      $data = $where['data'];

      $column = $data['column'];
      $operator = $data['operator'];
      $value = $data['value'];

      switch ($type){
        case 'basic':
          $condition[] = "{$column} {$operator} ?";
          $params[] = $value;
          break;
        case 'in':
          $values = is_array($value) ? $value : [];
          $placeholders = implode(',', array_fill(0, count($values), '?'));

          $condition[] = "{$column} IN ($placeholders)";
          $params = [...$params, ...$values];
          break;
      }
    }

    $sql = implode(' AND ', $condition);

    return ['sql' => $sql, 'params' => $params];
  }

  /**
   * @param array<string, mixed> $data
   */
  public function update(array $data): int {
    if (empty($data)){
      throw new InvalidArgumentException('Updated data can\'t be empty');
    }

    ['sql' => $sql, 'params' => $params] = $this->buildUpdate($data);

    return $this->connection->execute($sql, $params);
  }

  public function delete(): int {
    $wheres = $this->wheres;

    if (empty($wheres)){
      throw new InvalidArgumentException('Where condition must me specified');
    }

    $table = $this->table;

    ['sql' => $conditionString, 'params' => $params] = $this->buildWhere();
    $sql = "DELETE FROM {$table} WHERE {$conditionString}";

    return $this->connection->execute($sql, $params);
  }

  /**
   * @param array<int, mixed> $values
   */
  public function whereIn(string $column, array $values): self {
    $this->wheres[] = [
      'type' => 'in',
      'data' => [
        'column' => $column,
        'operator' => null,
        'value' => $values
      ]
    ];

    return $this;
  }
}