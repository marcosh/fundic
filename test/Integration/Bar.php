<?php

declare(strict_types=1);

namespace FundicTest\Integration;

use DateTimeImmutable;

final class Bar
{
    private $date;

    public function __construct(DateTimeImmutable $date)
    {
        $this->date = $date;
    }
}
