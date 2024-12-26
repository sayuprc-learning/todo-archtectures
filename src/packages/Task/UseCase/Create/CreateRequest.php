<?php

declare(strict_types=1);

namespace Task\UseCase\Create;

class CreateRequest
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $dueDate,
    ) {
    }
}
