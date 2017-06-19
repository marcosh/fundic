<?php

declare(strict_types=1);

namespace FundicTest\DataStructure;

use Fundic\DataStructure\Just;
use Fundic\DataStructure\Maybe;
use Fundic\DataStructure\Nothing;
use PHPUnit\Framework\TestCase;

final class MaybeTest extends TestCase
{
    public function testNothingCreatesAnInstanceOfNothing() : void
    {
        $nothing = Maybe::nothing();

        self::assertInstanceOf(Nothing::class, $nothing);
        self::assertInstanceOf(Maybe::class, $nothing);
    }

    public function testJustCreatesAnInstanceOnJust() : void
    {
        $just = Maybe::just(null);

        self::assertInstanceOf(Just::class, $just);
        self::assertInstanceOf(Maybe::class, $just);
    }

    public function testJustWrapsTheCorrectValue() : void
    {
        $just = Maybe::just(73);

        self::assertSame(73, $just->get());
    }

    public function testMapOnNothing() : void
    {
        $nothing = Maybe::nothing();

        self::assertEquals(Maybe::nothing(), $nothing->map(function ($x) {return $x+1;}));
    }

    public function testMapOnJust() : void
    {
        $just = Maybe::just(73);

        self::assertEquals(Maybe::just(74), $just->map(function ($x) {return $x+1;}));
    }
}
