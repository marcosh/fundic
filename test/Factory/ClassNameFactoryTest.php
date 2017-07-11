<?php

declare(strict_types=1);

namespace FundicTest\Factory;

use Fundic\Factory\ClassNameFactory;
use Fundic\TypedContainer;
use PHPUnit\Framework\TestCase;

final class ClassNameFactoryTest extends TestCase
{
    public function testInvokingCreatesObjectOfClassPassedInConstructor()
    {
        $factory = new ClassNameFactory(\stdClass::class);

        self::assertInstanceOf(\stdClass::class, $factory(TypedContainer::create(), ''));
    }

    public function testInvokingCreatesObjectsOfNameClass()
    {
        $factory = new ClassNameFactory();

        self::assertInstanceOf(\stdClass::class, $factory(TypedContainer::create(), \stdClass::class));
    }
}
