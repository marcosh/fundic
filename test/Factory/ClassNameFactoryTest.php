<?php

declare(strict_types=1);

namespace FundicTest\Factory;

use Fundic\Container;
use Fundic\Factory\ClassNameFactory;
use PHPUnit\Framework\TestCase;

final class ClassNameFactoryTest extends TestCase
{
    public function testInvokingCreatesObjectOfClassPassedInConstructor()
    {
        $factory = new ClassNameFactory(\stdClass::class);

        self::assertInstanceOf(\stdClass::class, $factory(Container::create(), ''));
    }

    public function testInvokingCreatesObjectsOfNameClass()
    {
        $factory = new ClassNameFactory();

        self::assertInstanceOf(\stdClass::class, $factory(Container::create(), \stdClass::class));
    }
}
