<?php

declare(strict_types=1);

namespace FundicTest\Factory\Decorator;

use Fundic\TypedContainer;
use Fundic\Factory\Decorator\Proxy;
use Fundic\Factory\ValueFactory;
use PHPUnit\Framework\TestCase;
use ProxyManager\Proxy\VirtualProxyInterface;
use Psr\Container\ContainerInterface;

final class ProxyTest extends TestCase
{
    public function testProxyWorksWithClassNamePassedToFactory() : void
    {
        $inner = new class() implements ValueFactory
        {
            public function __invoke(ContainerInterface $container, string $name)
            {
                return new Foo();
            }
        };
        $factory = new Proxy($inner);

        $object = $factory(TypedContainer::create(), Foo::class);

        self::assertInstanceOf(VirtualProxyInterface::class, $object);
        self::assertInstanceOf(Foo::class, $object);

        self::assertSame('baz', $object->bar);
    }

    public function testProxyWorksWithClassNamePassedToProxy() : void
    {
        $inner = new class() implements ValueFactory
        {
            public function __invoke(ContainerInterface $container, string $name)
            {
                return new Foo();
            }
        };
        $factory = new Proxy($inner, Foo::class);

        $object = $factory(TypedContainer::create(), '');

        self::assertInstanceOf(VirtualProxyInterface::class, $object);
        self::assertInstanceOf(Foo::class, $object);

        self::assertSame('baz', $object->bar);
    }
}
