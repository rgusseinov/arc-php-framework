<?php

declare(strict_types=1);

namespace Arc\Framework\Core;

use Arc\Framework\Providers\ServiceProviderInterface;
use ReflectionClass;
use Exception;
use ReflectionNamedType;

interface ContainerInterface {
    public function get(string $id): mixed;
    public function has(string $id): bool;
}

class Container implements ContainerInterface {
    /** @var array<string, array{factory: callable|null, shared: bool}> */
    private array $definitions = [];

    /** @var array<string, object> */
    private array $instances = [];

  public function bind(string $id, callable $factory): void {
    $this->definitions[$id] = ['factory' => $factory, 'shared' => false];
  }

  public function singleton(string $id, callable|null $factory = null): void {
    $this->definitions[$id] = ['factory' => $factory, 'shared' => true];
  }

  public function has(string $id): bool
  {
    if (array_key_exists($id, $this->definitions)){
        return true;
    }

    return class_exists($id);
  }

  public function get(string $id): mixed {
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

    throw new Exception("There is some class issue");
  }

    /**
     * @param class-string $id
     */
  private function autowire(string $id): object {
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

        if ($type instanceof ReflectionNamedType){
            if ($type->isBuiltin()) {
                if ($param->isDefaultValueAvailable()) {
                    $argsParams[] = $param->getDefaultValue();
                } else {
                    throw new Exception("Builtin type needs config: \${$param->getName()}");
                }
            } else {
                $argsParams[] = $this->get($type->getName());
            }
        } else {
            throw new Exception("Can't resolve union type for \${$param->getName()}");
        }
    }
    return $this->instances[$id] = $rc->newInstanceArgs($argsParams);
  }

  public function register(ServiceProviderInterface $provider): void {
    $provider->register($this); 
  }
}