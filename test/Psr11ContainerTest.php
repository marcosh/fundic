<?php

declare(strict_types=1);

namespace FundicTest;

use Fundic\Factory\ValueFactory;
use Fundic\Psr11Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Psr11ContainerTest extends TestCase
{
    public function testGetThrowsNotFoundExceptionOnMissingKey(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage('No entry was found for gigi identifier');

        $container = Psr11Container::create();

        $container->get('gigi');
    }

    public function testGetReturnsTheValueOnPresentKey() : void
    {
        $container = Psr11Container::create();

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

        self::assertEquals(73, $container->get('gigi'));
    }

    public function testGetReturnsExceptionOnError() : void
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Error while retrieving the entry with gigi identifier');

        $container = Psr11Container::create();

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

        $container->get('gigi');
    }
}
