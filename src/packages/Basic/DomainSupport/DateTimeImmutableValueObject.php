<?php

declare(strict_types=1);

namespace Basic\DomainSupport;

use DateTimeImmutable;

abstract class DateTimeImmutableValueObject
{
    public function __construct(public readonly DateTimeImmutable $value)
    {
    }
}
