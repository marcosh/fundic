<?php

declare(strict_types=1);

namespace Fundic\Decorator;

use Fundic\ContainerInterface;
use Fundic\DataStructure\Result\Exception;
use Fundic\DataStructure\Result\Just;
use Fundic\DataStructure\Result\NotFound;
use Fundic\Decorator\Exception\ContainerException;
use Fundic\Decorator\Exception\NotFoundException;
use Fundic\Factory\ValueFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ExceptionContainer implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function create(ContainerInterface $container): self
    {
        return new self($container);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        $result = $this->container->get($id);

        switch ($result) {
            case ($result instanceof Just):
                /** @var Just $result */
                return $result->get();
            case ($result instanceof NotFound):
                throw NotFoundException::forKey($id);
            case ($result instanceof Exception):
                /** @var Exception $result */
                throw ContainerException::forKeyWithInner($id, $result->inner());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($id) : bool
    {
        return $this->container->has($id);
    }

    public function add(string $id, ValueFactory $factory): self
    {
        return new self($this->container->add($id, $factory));
    }
}
