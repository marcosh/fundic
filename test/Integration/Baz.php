<?php

declare(strict_types=1);

namespace FundicTest\Integration;

class Baz
{
    private $foo;

    private $bar;

    public function __construct(Foo $foo, Bar $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function baz()
    {
        return 'baz message';
    }
}
