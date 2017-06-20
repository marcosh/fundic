<?php

declare(strict_types=1);

namespace Fundic\Factory;

use Psr\Container\ContainerInterface;

final class ClassNameFactory implements ValueFactory
{
    /**
     * @var string
     */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $this->className;
    }
}
