<?php

declare(strict_types=1);

namespace FundicTest\Factory;

use Fundic\Container;
use Fundic\Factory\ConstantFactory;
use PHPUnit\Framework\TestCase;

final class ConstantFactoryTest extends TestCase
{
    public function testInvokingReturnsTheConstant()
    {
        $factory = new ConstantFactory(73);

        self::assertSame(73, $factory(Container::create()));
    }
}
