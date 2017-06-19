<?php

declare(strict_types=1);

namespace Fundic\Factory\Decorator;

use Fundic\Factory\ValueFactory;
use Psr\Container\ContainerInterface;

final class Memoize implements ValueFactory
{
    /**
     * @var ValueFactory
     */
    private $inner;

    private $result;

    /**
     * @var bool
     */
    private $alreadyComputed = false;

    public function __construct(ValueFactory $inner)
    {
        $this->inner = $inner;
    }

    public function __invoke(ContainerInterface $container)
    {
        if (!$this->alreadyComputed) {
            $this->result = ($this->inner)($container);
            $this->alreadyComputed = true;
        }

        return $this->result;
    }
}
