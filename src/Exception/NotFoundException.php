<?php

declare(strict_types=1);

namespace Fundic\Exception;

use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends \RuntimeException implements ExceptionInterface, NotFoundExceptionInterface
{
    public static function forKey(string $id) : self
    {
        return new self(sprintf('No entry was found for %s identifier', $id));
    }
}
