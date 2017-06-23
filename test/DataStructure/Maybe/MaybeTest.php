<?php

declare(strict_types=1);

namespace FundicTest\DataStructure;

use Fundic\DataStructure\Maybe\Just;
use Fundic\DataStructure\Maybe\Maybe;
use Fundic\DataStructure\Maybe\Nothing;
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
}
