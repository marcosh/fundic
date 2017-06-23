<?php

declare(strict_types=1);

namespace Fundic\DataStructure\Result;

interface Exception
{
    public function inner() : \Throwable;
}
