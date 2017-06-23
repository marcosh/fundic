<?php

declare(strict_types=1);

namespace Fundic\DataStructure\Maybe;

class Maybe
{
    private function __construct()
    {
    }

    final public static function nothing()
    {
        return new class() extends Maybe implements Nothing {};
    }

    final public static function just($value)
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
}
