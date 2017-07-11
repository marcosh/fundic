<?php

declare(strict_types=1);

namespace Fundic;

use Fundic\DataStructure\Dictionary;
use Fundic\DataStructure\Maybe\Just;
use Fundic\DataStructure\Maybe\Nothing;
use Fundic\Exception\ContainerException;
use Fundic\Exception\NotFoundException;
use Fundic\Factory\ValueFactory;
use Psr\Container\NotFoundExceptionInterface;

final class Psr11Container implements Container
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
    public function has($id) : bool
    {
        return $this->values->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $maybeFactory = $this->values->get($id);

        $container = $this;

        try {
            switch (true) {
                case ($maybeFactory instanceof Just):
                    /** @var Just $maybeFactory */
                    return ($maybeFactory->get())($container, $id);
                case ($maybeFactory instanceof Nothing):
                    throw NotFoundException::forKey($id);
            }
        } catch (\Throwable $e) {
            if ($e instanceof NotFoundExceptionInterface) {
                throw $e;
            }

            throw ContainerException::forKeyWithInner($id, $e);
        }
    }

    public function add(string $id, ValueFactory $factory) : Container
    {
        return new self($this->values->add($id, $factory));
    }
}
