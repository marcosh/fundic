<?php

declare(strict_types=1);

namespace Fundic\Factory\Decorator;

use Fundic\Factory\ValueFactory;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;
use Psr\Container\ContainerInterface;

final class Proxy implements ValueFactory
{
    /**
     * @var ValueFactory
     */
    private $inner;

    /**
     * @var string
     */
    private $className;

    public function __construct(
        ValueFactory $inner,
        ?string $className = null
    ) {
        $this->inner = $inner;
        $this->className = $className;
    }

    public function __invoke(ContainerInterface $container, string $name)
    {
        $inner = $this->inner;

        $proxyFactory = new LazyLoadingValueHolderFactory();
        $initializer = function (
            & $wrappedObject,
            LazyLoadingInterface $proxy
        ) use ($container, $inner, $name) {
            $proxy->setProxyInitializer(null);
            $wrappedObject = $inner($container, $name);

            return true;
        };

        return $proxyFactory->createProxy($this->className ?? $name, $initializer);
    }
}
