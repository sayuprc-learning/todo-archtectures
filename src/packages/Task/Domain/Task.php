<?php

declare(strict_types=1);

namespace Task\Domain;

class Task
{
    public function __construct(
        public readonly Id $id,
        public readonly Title $title,
        public readonly Description $description,
        public readonly Completed $completed,
        public readonly DueDate $dueDate,
    ) {
    }

    public function isCompleted(): bool
    {
        return $this->completed->value;
    }
}
