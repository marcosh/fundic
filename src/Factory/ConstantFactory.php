<?php

declare(strict_types=1);

namespace Fundic\Factory;

use Psr\Container\ContainerInterface;

final class ConstantFactory implements ValueFactory
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __invoke(ContainerInterface $container, string $name)
    {
        return $this->value;
    }
}
