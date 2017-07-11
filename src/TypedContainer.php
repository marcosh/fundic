<?php

declare(strict_types=1);

namespace Fundic;

use Fundic\DataStructure\Dictionary;
use Fundic\DataStructure\Maybe\Just;
use Fundic\DataStructure\Maybe\Nothing;
use Fundic\DataStructure\Result\Result;
use Fundic\Factory\ValueFactory;

final class TypedContainer implements Container
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
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return Result
     */
    public function get($id) : Result
    {
        $maybeFactory = $this->values->get($id);

        $container = $this;

        try {
            switch (true) {
                case ($maybeFactory instanceof Just):
                    /** @var Just $maybeFactory */
                    return Result::just(($maybeFactory->get())($container, $id));
                case ($maybeFactory instanceof Nothing):
                    return Result::notFound();
            }
        } catch (\Throwable $e) {
            return Result::exception($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($id) : bool
    {
        return $this->values->has($id);
    }

    public function add(string $id, ValueFactory $factory) : Container
    {
        return new self($this->values->add($id, $factory));
    }
}
