<?php

declare(strict_types=1);

namespace FundicTest;

use Fundic\Container;
use Fundic\DataStructure\Dictionary;
use Fundic\DataStructure\Maybe\Maybe;
use Fundic\DataStructure\Result\Result;
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

    public function testGetReturnsNotFoundOnMissingKey(): void
    {
        $container = Container::create();

        self::assertEquals(Result::notFound(), $container->get('gigi'));
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

        self::assertEquals(Result::just(73), $container->get('gigi'));
    }

    public function testGetReturnsExceptionOnError() : void
    {
        $container = Container::create();

        $exception = new \Exception();

        $container = $container->add(
            'gigi',
            new class($exception) implements ValueFactory
            {
                private $exception;

                public function __construct(\Exception $exception)
                {
                    $this->exception = $exception;
                }

                public function __invoke(ContainerInterface $container, string $name)
                {
                    throw $this->exception;
                }
            }
        );

        self::assertEquals(Result::exception($exception), $container->get('gigi'));
    }
}
