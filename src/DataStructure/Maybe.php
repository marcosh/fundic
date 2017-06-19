<?php

declare(strict_types=1);

namespace Fundic\DataStructure;

class Maybe
{
    private function __construct()
    {
    }

    public static function nothing()
    {
        return new class() extends Maybe implements Nothing {};
    }

    public static function just($value)
    {
        return new class($value) extends Maybe implements Just {
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

    public function map(callable $f)
    {
        if ($this instanceof Just) {
            return Maybe::just($f($this->get()));
        }

        return Maybe::nothing();
    }
}
