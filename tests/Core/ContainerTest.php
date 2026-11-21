<?php

namespace Tests\Core;

use Arc\Framework\Core\Container;
use Exception;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\ServiceNeedsString;
use Tests\Fixtures\ServiceWithDefault;
use Tests\Fixtures\ServiceWithDependency;
use Tests\Fixtures\SimpleService;

final class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
    }

    public function testGetCreatesClassWithoutConstructor(): void
    {
        $this->assertInstanceOf(SimpleService::class, $this->container->get(SimpleService::class));
    }

    public function testAutowiresDependency(): void
    {
        $service = $this->container->get(ServiceWithDependency::class);

        $this->assertInstanceOf(ServiceWithDependency::class, $service);
        $this->assertInstanceOf(SimpleService::class, $service->simple);
    }

    public function testAutowiredDependencyRespectsSingleton(): void
    {
        $this->container->singleton(SimpleService::class);

        $service = $this->container->get(ServiceWithDependency::class);

        $this->assertSame($this->container->get(SimpleService::class), $service->simple);
    }

    public function testUnregisteredClassIsNotShared(): void
    {
        // Documents current behavior: autowired classes without a binding are created anew.
        $this->assertNotSame(
            $this->container->get(SimpleService::class),
            $this->container->get(SimpleService::class)
        );
    }

    public function testBindReturnsNewInstanceEachTime(): void
    {
        $this->container->bind(SimpleService::class, fn () => new SimpleService());

        $this->assertNotSame(
            $this->container->get(SimpleService::class),
            $this->container->get(SimpleService::class)
        );
    }

    public function testSingletonWithFactoryReturnsSameInstance(): void
    {
        $this->container->singleton(SimpleService::class, fn () => new SimpleService());

        $this->assertSame(
            $this->container->get(SimpleService::class),
            $this->container->get(SimpleService::class)
        );
    }

    public function testSingletonWithoutFactoryReturnsSameInstance(): void
    {
        $this->container->singleton(SimpleService::class);

        $this->assertSame(
            $this->container->get(SimpleService::class),
            $this->container->get(SimpleService::class)
        );
    }

    public function testFactoryReceivesContainer(): void
    {
        $received = null;

        $this->container->bind('custom', function (Container $container) use (&$received) {
            $received = $container;

            return new SimpleService();
        });

        $this->container->get('custom');

        $this->assertSame($this->container, $received);
    }

    public function testHasKnowsRegisteredIdsAndExistingClasses(): void
    {
        $this->container->bind('custom', fn () => new SimpleService());

        $this->assertTrue($this->container->has('custom'));
        $this->assertTrue($this->container->has(SimpleService::class));
    }

    public function testHasReturnsFalseForUnknown(): void
    {
        $this->assertFalse($this->container->has('does.not.exist'));
    }

    public function testGetUnknownIdThrows(): void
    {
        $this->expectException(Exception::class);

        $this->container->get('does.not.exist');
    }

    public function testBuiltinWithoutDefaultThrows(): void
    {
        $this->expectException(Exception::class);

        $this->container->get(ServiceNeedsString::class);
    }

    public function testBuiltinWithDefaultUsesDefault(): void
    {
        $service = $this->container->get(ServiceWithDefault::class);

        $this->assertSame('default', $service->name);
    }
}
