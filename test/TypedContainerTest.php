<?php

declare(strict_types=1);

namespace FundicTest;

use Fundic\DataStructure\Result\Result;
use Fundic\Factory\ValueFactory;
use Fundic\TypedContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class TypedContainerTest extends TestCase
{
    public function testGetReturnsNotFoundOnMissingKey(): void
    {
        $container = TypedContainer::create();

        self::assertEquals(Result::notFound(), $container->get('gigi'));
    }

    public function testGetReturnsJustTheValueOnPresentKey() : void
    {
        $container = TypedContainer::create();

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
        $container = TypedContainer::create();

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
