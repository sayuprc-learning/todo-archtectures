<?php

declare(strict_types=1);

namespace Task\Domain;

use Basic\DomainSupport\StringValueObject;
use Exception;

class Title extends StringValueObject
{
    private const MIN_LENGTH = 1;

    private const MAX_LENGTH = 255;

    public function __construct(string $value)
    {
        $length = mb_strlen(trim($value));

        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new Exception('タイトルは 1 文字以上 255 文字以内である必要があります');
        }

        parent::__construct($value);
    }
}
