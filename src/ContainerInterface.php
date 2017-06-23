<?php

declare(strict_types=1);

namespace Fundic;

use Fundic\Factory\ValueFactory;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    public function add(string $id, ValueFactory $factory) : self;
}
