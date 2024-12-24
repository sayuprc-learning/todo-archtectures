<?php

declare(strict_types=1);

namespace Basic\DomainSupport;

abstract class StringValueObject
{
    public function __construct(public readonly string $value)
    {
    }
}
