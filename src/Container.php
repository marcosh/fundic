<?php

declare(strict_types=1);

namespace Fundic;

use Fundic\DataStructure\Dictionary;
use Fundic\DataStructure\Maybe;
use Fundic\Factory\ValueFactory;
use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /**
     * @var Dictionary
     */
    private $values = [];

    private function __construct(Dictionary $values)
    {
        $this->values = $values;
    }

    public static function create() : self
    {
        return new self(Dictionary::empty());
    }

    /**
     * {@inheritdoc}
     */
    public function get($id) : Maybe
    {
        $maybeFactory = $this->values->get($id);

        $container = $this;

        return $maybeFactory->map(
            function(ValueFactory $factory) use ($container, $id) {
                return $factory($container, $id);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function has($id) : bool
    {
        return $this->values->has($id);
    }

    public function add(string $id, ValueFactory $factory) : self
    {
        return new self($this->values->add($id, $factory));
    }
}
