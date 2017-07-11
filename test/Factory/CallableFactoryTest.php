<?php

declare(strict_types=1);

namespace FundicTest\Factory;

use Fundic\TypedContainer;
use Fundic\Factory\CallableFactory;
use PHPUnit\Framework\TestCase;

final class CallableFactoryTest extends TestCase
{
    public function testInvokingReturnsTheCallableResult()
    {
        $callable = new class() {
            private $n = null;

            public function __invoke()
            {
                if (null === $this->n) {
                    $this->n = random_int(-1000, 1000);
                }

                return $this->n;
            }
        };

        $factory = new CallableFactory($callable);

        self::assertSame($callable(), $factory(TypedContainer::create(), ''));
    }
}
