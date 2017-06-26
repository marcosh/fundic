<?php

declare(strict_types=1);

namespace FundicTest\Decorator;

use Fundic\Container;
use Fundic\ContainerInterface;
use Fundic\Decorator\Exception\ContainerException;
use Fundic\Decorator\Exception\NotFoundException;
use Fundic\Decorator\ExceptionContainer;
use Fundic\Factory\CallableFactory;
use Fundic\Factory\ConstantFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

final class ExceptionContainerTest extends TestCase
{
    private $prophet;

    protected function setUp()
    {
        $this->prophet = new Prophet();
    }

    public function testAddDelegatesToInnerContainer() : void
    {
        $inner = $this->prophet->prophesize(ContainerInterface::class);

        $container = new ExceptionContainer($inner->reveal());

        $factory = new ConstantFactory(73);
        $inner->add()->shouldBeCalled()->withArguments(['gigi', $factory]);

        $container->add('gigi', $factory);
    }

    public function testHasDelegatesToInnerContainer() : void
    {
        $inner = $this->prophet->prophesize(ContainerInterface::class);

        $container = new ExceptionContainer($inner->reveal());

        $inner->has()->shouldBeCalled()->withArguments(['gigi'])->willReturn(true);

        $container->has('gigi');
    }

    public function testGetOnPresentKeyUnwrapsResult() : void
    {
        $inner = Container::create();
        $inner = $inner->add('gigi', new ConstantFactory(73));

        $container = ExceptionContainer::create($inner);

        self::assertSame(73, $container->get('gigi'));
    }

    public function testGetOnMissingKeyThrowsNotFoundException() : void
    {
        $inner = Container::create();

        $container = ExceptionContainer::create($inner);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('No entry was found for gigi identifier');

        $container->get('gigi');
    }

    public function testGetOnExceptionThrowsContainerException() : void
    {
        $inner = Container::create();

        $exception = new \RuntimeException();
        $inner = $inner->add('gigi', new CallableFactory(function () use ($exception) {
            throw $exception;
        }));

        $container = ExceptionContainer::create($inner);

        try {
            $container->get('gigi');

            throw new Exception('Failed asserting that a ContainerException is thrown   ');
        } catch (ContainerException $e) {
            self::assertSame('Error while retrieving the entry with gigi identifier', $e->getMessage());
            self::assertSame($exception, $e->getPrevious());
        }
    }

    protected function tearDown()
    {
        $this->prophet->checkPredictions();
    }
}
