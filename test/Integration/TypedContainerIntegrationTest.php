<?php

declare(strict_types=1);

namespace FundicTest\Integration;

use Fundic\Factory\CallableFactory;
use Fundic\Factory\ClassNameFactory;
use Fundic\Factory\Decorator\Memoize;
use Fundic\Factory\Decorator\Proxy;
use Fundic\TypedContainer;
use PHPUnit\Framework\TestCase;
use ProxyManager\Proxy\ProxyInterface;
use Psr\Container\ContainerInterface;

final class TypedContainerIntegrationTest extends TestCase
{
    public function testTypedContainerIntegration()
    {
        $container = TypedContainer::create();

        $container = $container->add(
            Foo::class,
            new ClassNameFactory(Foo::class)
        );

        $container = $container->add(
            Bar::class,
            new Memoize(
                new CallableFactory(
                    function (ContainerInterface $container) {
                        return new Bar(date_create_immutable());
                    }
                )
            )
        );

        $container = $container->add(
            Baz::class,
            new Proxy(
                new CallableFactory(
                    function (ContainerInterface $container) {
                        return new Baz(
                            $container->get(Foo::class)->get(),
                            $container->get(Bar::class)->get()
                        );
                    }
                )
            )
        );

        self::assertInstanceOf(Foo::class, $container->get(Foo::class)->get());

        $bar1 = $container->get(Bar::class)->get();
        $bar2 = $container->get(Bar::class)->get();

        self::assertSame($bar1, $bar2);

        $baz = $container->get(Baz::class)->get();

        self::assertInstanceOf(ProxyInterface::class, $baz);
        self::assertSame('baz message', $baz->baz());
    }
}
