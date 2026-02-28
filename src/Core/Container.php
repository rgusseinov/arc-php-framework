<?php

interface ContainerInterface {
    public function get(string $id);
    public function has(string $id): bool;
}

class Container implements ContainerInterface {
  private array $definitions = []; // как создавать
  private array $instances = [];   // что уже создано

  public function bind(string $id, callable $factory)
  {
    $this->definitions[$id] = ['factory' => $factory, 'shared' => false];
  }

  public function singleton(string $id, callable|null $factory = null)
  {
    $this->definitions[$id] = ['factory' => $factory, 'shared' => true];
  }

  public function has(string $id): bool
  {
    if (array_key_exists($id, $this->definitions)){
        return true;
    }

    return class_exists($id);
  }

  public function get(string $id){
    // если instance уже создан → вернуть
    // если есть factory → создать через factory
    // если class существует → autowire
    // иначе → exception
    
    if (array_key_exists($id, $this->definitions)) {
        $definition = $this->definitions[$id];
        $factory = $definition['factory'];
        $shared = $definition['shared'];

        // Instance возвращается
        if (isset($this->instances[$id]) && $shared == true){
            return $this->instances[$id];
        }

        // 2. Factory method
        // Factory вызывается,

        if ($factory !== null){
            $object = $factory($this);
        } else {
            $object = $this->autowire($id);
        }


        if ($shared == false){
            return $object;
        }

        return $this->instances[$id] = $object;
    }

    if (class_exists($id)){
        return $this->autowire($id);
    }

    return throw new Exception("There is some class issue");
  }

  private function autowire($id){
    $rc = new ReflectionClass($id);

    if (!$rc->isInstantiable()){
        throw new Exception("The class is not instantiable");
    }

    $constructor = $rc->getConstructor();

    if (!$constructor){
        $object = new $id;

        return $this->instances[$id] = $object;
    }

    $params = $constructor->getParameters();

    $argsParams = [];

    foreach ($params as $param) {
        $type = $param->getType();

        if (!$type){
        throw new Exception("Can't resolve parametrs {$param->getName()}");
        }

        if ($type->isBuiltin()) {
            if ($param->isDefaultValueAvailable()) {
                $argsParams[] = $param->getDefaultValue();
            } else {
                throw new Exception("Builtin type needs config: \${$param->getName()}");
            }
        } else {
            $argsParams[] = $this->get($type->getName());
        }
    }
    return $this->instances[$id] = $rc->newInstanceArgs($argsParams);
  }

  public function register($provider)
  {
    $provider->register($this); 
  }
}