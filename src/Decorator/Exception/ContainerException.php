<?php

declare(strict_types=1);

namespace Fundic\Decorator\Exception;

use Psr\Container\ContainerExceptionInterface;
use Throwable;

final class ContainerException extends \RuntimeException implements ExceptionInterface, ContainerExceptionInterface
{
    public static function forKeyWithInner(string $id, Throwable $inner)
    {
        return new self(
            sprintf('Error while retrieving the entry with %s identifier', $id),
            0,
            $inner
        );
    }
}
