<?php

declare(strict_types=1);

namespace FundicTest\Factory\Decorator;

use Fundic\TypedContainer;
use Fundic\Factory\Decorator\Memoize;
use Fundic\Factory\ValueFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class MemoizeTest extends TestCase
{
    public function testMemoizeDoesNotCallTwiceTheInnerFactory() : void
    {
        $inner = new class() implements ValueFactory
        {
            private $i = 0;

            public function __invoke(ContainerInterface $container, string $name)
            {
                $this->i = $this->i +1;
                return $this->i;
            }
        };
        $factory = new Memoize($inner);

        self::assertSame($factory(TypedContainer::create(), ''), $factory(TypedContainer::create(), ''));
    }
}
