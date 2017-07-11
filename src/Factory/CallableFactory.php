<?php

declare(strict_types=1);

namespace Fundic\Factory;

use Psr\Container\ContainerInterface;

final class CallableFactory implements ValueFactory
{
    /**
     * @var callable
     */
    private $callableFactory;

    public function __construct(callable $callableFactory)
    {
        $this->callableFactory = $callableFactory;
    }

    public function __invoke(ContainerInterface $container, string $name)
    {
        return ($this->callableFactory)($container, $name);
    }
}
