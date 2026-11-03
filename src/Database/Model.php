<?php

namespace Arc\Framework\Database;

use Arc\Framework\Core\Container;
use Exception;

/**
 * @property int $id
 */
abstract class Model {
	protected static string $table;
	protected bool $exists = false;

	/** @var array<string, mixed> */
	protected array $attributes = [];

	/** @var list<string> */
	protected array $fillable = [];

	protected static ?Container $container = null;
	protected ?string $relation = null;

	/** @var array<string, mixed> */
	protected array $relations = [];

	final public function __construct(){}

	public static function setContainer(Container $container): void {
		self::$container = $container;
	}

	private static function queryBuilder(): QueryBuilder {
		if (self::$container === null) {
			throw new Exception("Container is not set. Call Model::setContainer() first.");
		}

		$queryBuilder = self::$container->get(QueryBuilder::class);

		if (!$queryBuilder instanceof QueryBuilder) {
			throw new Exception("Container returned invalid QueryBuilder.");
		}

		return $queryBuilder;
	}

	/**
	 * @param array<string, mixed> $result
	 */
	private function setAttributes(array $result): void {
		$this->attributes = $result;

		$this->exists = true;
	}

	public function __get(string $name): mixed {
		if (array_key_exists($name, $this->attributes)){
			return $this->attributes[$name];
		}

		if (array_key_exists($name, $this->relations)){
			return $this->relations[$name];
		}

		throw new Exception("Property {$name} does not exist.");
	}

	public function __set(string $name, mixed $value): void {
		if (!in_array($name, $this->fillable, true)) {
			throw new Exception("Property {$name} is not allowed.");
		}

		$this->attributes[$name] = $value;
	}

	public static function find(int $id): static {
		$result = self::queryBuilder()->table(static::$table)->where('id', '=', $id)->first();

		if ($result == null){
			throw new Exception("Record ID {$id} not found.");
		}

		$childClassInstance = new static();
		$childClassInstance->setAttributes($result);

		return $childClassInstance;
	}

	public function save(): static|bool {
		$queryBuilder = self::queryBuilder();

		if ($this->exists){
			$result = $queryBuilder->table(static::$table)->where('id', '=', $this->attributes['id'])->update($this->attributes);

			return (bool)$result;
		}

		$lastInsertId = $queryBuilder->table(static::$table)->insert($this->attributes);
		$this->attributes['id'] = $lastInsertId;

		$this->exists = true;

		return $this;
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	public static function all(): array {
		return self::queryBuilder()->table(static::$table)->get();
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	protected function hasMany(string $relatedModel, string $foreignKey = '', string $localKey = 'id'): array {
		$table = $relatedModel::getTableName();
		$field = !empty($foreignKey) ? $foreignKey : strtolower(get_class($this)) . '_id';

		return self::queryBuilder()->table($table)->where($field, '=', $this->attributes['id'])->get();
	}

	protected function belongsTo(string $relatedModel, string $foreignKey = '', string $ownerKey = 'id'): Model {
		$field = !empty($foreignKey) ? $foreignKey : strtolower($relatedModel) . '_id';

		$id = $this->attributes[$field];

		return $relatedModel::find($id);
	}

	public static function getTableName(): string {
		return static::$table;
	}

	public static function with(string $relation): static {
		$model = new static();
		$model->relation = $relation;

		return $model;
	}

	/**
	 * @return list<static>
	 */
	public function get(): array {
		$table = static::$table;

		$queryBuilder = self::queryBuilder();
		$rows = $queryBuilder->table($table)->get();

		$objects = array_map(function($row){
			$childClassInstance = new static();
			$childClassInstance->setAttributes($row);

			return $childClassInstance;
		}, $rows);

		if ($this->relation !== null) {
			// eager loading
			$ids = array_map(fn($user) => $user->id, $objects);
			$relationRecords = $queryBuilder->table($this->relation)->whereIn("user_id", $ids)->get();

			$postsGroupedByUsers = [];

			foreach ($relationRecords as $rel){
				$postsGroupedByUsers[$rel['user_id']][] = $rel;
			}

			foreach ($objects as $user) {
				$user->setRelation('posts', $postsGroupedByUsers[$user->id] ?? []);
			}
		}

		return $objects;
	}

	public function setRelation(string $name, mixed $value): void {
		$this->relations[$name] = $value;
	}
}