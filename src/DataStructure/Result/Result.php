<?php

declare(strict_types=1);

namespace Fundic\DataStructure\Result;

class Result
{
    private function __construct()
    {
    }

    final public static function just($value)
    {
        return new class($value) extends Result implements Just {
            private $value;

            public function __construct($value)
            {
                $this->value = $value;
            }

            public function get()
            {
                return $this->value;
            }
        };
    }

    final public static function notFound()
    {
        return new class() extends Result implements NotFound {};
    }

    final public static function exception(\Throwable $exception)
    {
        return new class($exception) extends Result implements Exception {
            private $exception;

            public function __construct(\Throwable $exception)
            {
                $this->exception = $exception;
            }

            public function inner() : \Throwable
            {
                return $this->exception;
            }
        };
    }
}
