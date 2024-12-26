<?php

declare(strict_types=1);

namespace Basic\DomainSupport;

abstract class BooleanValueObject
{
    public function __construct(public readonly bool $value)
    {
    }
}
