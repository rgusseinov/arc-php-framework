<?php

abstract class Model {
	protected static string $table;
	protected bool $exists = false;
	protected array $attributes = [];
	protected array $fillable;
	protected static $container;
	protected ?string $relation = null;
	protected array $relations = [];

	public static function setContainer($container){
		self::$container = $container;
	}

	// Подумай, соответствует ли название тому, что метод делает.
	private function setAttributes($result){
		$this->attributes = $result;

		$this->exists = true;
	}

	public function __get(string $name){
		if (array_key_exists($name, $this->attributes)){
			return $this->attributes[$name];
		}

		if (array_key_exists($name, $this->relations)){
			return $this->relations[$name];
		}

		throw new Exception("Property {$name} does not exist.");
	}

	public function __set($name, $value){
		if (!in_array($name, $this->fillable, true)) {
			throw new Exception("Property {$name} is not allowed.");
		}

		$this->attributes[$name] = $value;
	}

	public static function find(int $id){
		$queryBuilder = self::$container->get(QueryBuilder::class);
		$result = $queryBuilder->table(static::$table)->where('id', '=', $id)->first();

		if ($result == null){
			throw new Exception("Record ID {$id} not found.");
		}

		$childClassInstance = new static();
		$childClassInstance->setAttributes($result);

		return $childClassInstance;
	}

	public function save(){
		$queryBuilder = self::$container->get(QueryBuilder::class);

		if ($this->exists){
			$result = $queryBuilder->table(static::$table)->where('id', '=', $this->attributes['id'])->update($this->attributes);

			return (bool)$result;
		}

		$lastInsertId = $queryBuilder->table(static::$table)->insert($this->attributes);
		$this->attributes['id'] = $lastInsertId;

		$this->exists = true;

		return $this;
	}

	public static function all(){
		$queryBuilder = self::$container->get(QueryBuilder::class);

		return $queryBuilder->table(static::$table)->get();
	}

	protected function hasMany(string $relatedModel, string $foreignKey = '', string $localKey = 'id'){
		$table = $relatedModel::getTableName();
		$field = !empty($foreignKey) ? $foreignKey : strtolower(get_class($this)) . '_id';

		$queryBuilder = self::$container->get(QueryBuilder::class);

		$posts = $queryBuilder->table($table)->where($field, '=', $this->attributes['id'])->get();

		return $posts;
	}

	protected function belongsTo(string $relatedModel, string $foreignKey = '', string $ownerKey = 'id'){	
		$field = !empty($foreignKey) ? $foreignKey : strtolower($relatedModel) . '_id';

		$id = $this->attributes[$field];

		return $relatedModel::find($id);
	}

	public static function getTableName(): string {
		return static::$table;
	}

	public static function with(string $relation): self {
    $model = new static();
		$model->relation = $relation;

		return $model;
  }

	public function get(): array {
		$table = static::$table;

		$queryBuilder = self::$container->get(QueryBuilder::class);
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