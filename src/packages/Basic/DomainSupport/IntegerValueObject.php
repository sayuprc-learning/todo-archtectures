<?php

declare(strict_types=1);

namespace Basic\DomainSupport;

abstract class IntegerValueObject
{
    public function __construct(public readonly int $value)
    {
    }
}
