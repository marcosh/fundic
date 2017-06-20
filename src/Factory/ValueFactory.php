<?php

declare(strict_types=1);

namespace Fundic\Factory;

use Psr\Container\ContainerInterface;

interface ValueFactory
{
    /**
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $name);
}
