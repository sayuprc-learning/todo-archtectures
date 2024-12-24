<?php

declare(strict_types=1);

namespace Task\Domain;

class Task
{
    public function __construct(
        public readonly Id $id,
        public readonly Title $title,
        public readonly Description $description,
        public readonly DueDate $dueDate,
    ) {
    }
}
