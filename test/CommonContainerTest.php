<?php

declare(strict_types=1);

namespace FundicTest;

use Fundic\Container;
use Fundic\DataStructure\Dictionary;
use Fundic\Factory\ValueFactory;
use Fundic\Psr11Container;
use Fundic\TypedContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class CommonContainerTest extends TestCase
{
    public function containers()
    {
        return [
            [TypedContainer::create()],
            [Psr11Container::create()]
        ];
    }

    /**
     * @dataProvider containers
     */
    public function testCreateGeneratesAnEmptyDictionary(Container $container) : void
    {
        $containerReflection = new \ReflectionObject($container);
        $dictionaryReflection = $containerReflection->getProperty('values');
        $dictionaryReflection->setAccessible(true);

        self::assertEquals(Dictionary::empty(), $dictionaryReflection->getValue($container));
    }

    /**
     * @dataProvider containers
     */
    public function testAddPreservesImmutability(Container $container) : void
    {
        $container->add(
            'gigi',
            new class() implements ValueFactory {
                public function __invoke(ContainerInterface $container, string $name) {}
            }
        );

        self::assertFalse($container->has('gigi'));
    }

    /**
     * @dataProvider containers
     */
    public function testHasReturnsFalseOnMissingKey(Container $container) : void
    {
        self::assertFalse($container->has('gigi'));
    }

    /**
     * @dataProvider containers
     */
    public function testHasReturnsTrueOnPresentKey(Container $container): void
    {
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
}
