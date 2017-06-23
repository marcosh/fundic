<?php

declare(strict_types=1);

namespace Fundic\DataStructure;

use Fundic\DataStructure\Maybe\Maybe;

final class Dictionary
{
    private $values = [];

    private function __construct()
    {
    }

    public static function empty() : self
    {
        return new self();
    }

    public function has(string $id) : bool
    {
        return array_key_exists($id, $this->values);
    }

    public function get(string $id) : Maybe
    {
        if (array_key_exists($id, $this->values)) {
            return Maybe::just($this->values[$id]);
        }

        return Maybe::nothing();
    }

    public function add(string $id, $value) : self
    {
        $instance = clone $this;
        $instance->values[$id] = $value;

        return $instance;
    }
}
