<?php

declare(strict_types=1);

namespace Fundic\Factory;

use Psr\Container\ContainerInterface;

final class ClassNameFactory implements ValueFactory
{
    /**
     * @var string|null
     */
    private $className;

    public function __construct(?string $className = null)
    {
        $this->className = $className;
    }

    public function __invoke(ContainerInterface $container, string $name)
    {
        $className = $this->className ?? $name;

        return new $className;
    }
}
