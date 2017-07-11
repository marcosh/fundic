<?php

declare(strict_types=1);

namespace Fundic;

use Fundic\Factory\ValueFactory;
use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface
{
    public function add(string $id, ValueFactory $factory) : self;
}
