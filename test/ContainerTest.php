<?php

declare(strict_types=1);

namespace FundicTest;

use Fundic\Container;
use Fundic\DataStructure\Dictionary;
use Fundic\DataStructure\Maybe;
use Fundic\Factory\ValueFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ContainerTest extends TestCase
{
    public function testCreateGeneratesAnEmptyDictionary() : void
    {
        $container = Container::create();

        $containerReflection = new \ReflectionObject($container);
        $dictionaryReflection = $containerReflection->getProperty('values');
        $dictionaryReflection->setAccessible(true);

        self::assertEquals(Dictionary::empty(), $dictionaryReflection->getValue($container));
    }

    public function testAddPreservesImmutability() : void
    {
        $container = Container::create();

        $container->add(
            'gigi',
            new class() implements ValueFactory {
                public function __invoke(ContainerInterface $container, string $name) {}
            }
        );

        self::assertFalse($container->has('gigi'));
    }

    public function testHasReturnsFalseOnMissingKey() : void
    {
        $container = Container::create();

        self::assertFalse($container->has('gigi'));
    }

    public function testHasReturnsTrueOnPresentKey(): void
    {
        $container = Container::create();

        $container = $container->add(
            'gigi',
            new class() implements ValueFactory
            {
                public function __invoke(ContainerInterface $container, string $name)
                {
                    return 73;
                }
            }
        );

        self::assertTrue($container->has('gigi'));
    }

    public function testGetReturnsNothingOnMissingKey(): void
    {
        $container = Container::create();

        self::assertEquals(Maybe::nothing(), $container->get('gigi'));
    }

    public function testGetReturnsJustTheValueOnPresentKey() : void
    {
        $container = Container::create();

        $container = $container->add(
            'gigi',
            new class() implements ValueFactory
            {
                public function __invoke(ContainerInterface $container, string $name)
                {
                    return 73;
                }
            }
        );

        self::assertEquals(Maybe::just(73), $container->get('gigi'));
    }
}
