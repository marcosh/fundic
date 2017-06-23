<?php

declare(strict_types=1);

namespace FundicTest\DataStructure\Result;

use Fundic\DataStructure\Result\Exception;
use Fundic\DataStructure\Result\Just;
use Fundic\DataStructure\Result\NotFound;
use Fundic\DataStructure\Result\Result;
use PHPUnit\Framework\TestCase;

final class ResultTest extends TestCase
{
    public function testNotFoundCreatesAnInstanceOfNotFound() : void
    {
        $notFound = Result::notFound();

        self::assertInstanceOf(NotFound::class, $notFound);
        self::assertInstanceOf(Result::class, $notFound);
    }

    public function testJustCreatesAnInstanceOfJust() : void
    {
        $just = Result::just(null);

        self::assertInstanceOf(Just::class, $just);
        self::assertInstanceOf(Result::class, $just);
    }

    public function testJustWrapsTheCorrectValue() : void
    {
        $just = Result::just(73);

        self::assertSame(73, $just->get());
    }

    public function testExceptionCreatesAnInstanceOfException() : void
    {
        $exception = Result::exception(new \Exception());

        self::assertInstanceOf(Exception::class, $exception);
        self::assertInstanceOf(Result::class, $exception);
    }

    public function testExceptionWrapsTheCorrectException() : void
    {
        $inner = new \Exception();
        $exception = Result::exception($inner);

        self::assertSame($inner, $exception->inner());
    }
}
