<?php

declare(strict_types=1);

namespace Task\UseCase\List;

use Task\Domain\Task;

class ListResponse
{
    /**
     * @param array<Task> $completedTasks
     * @param array<Task> $uncompletedTasks
     */
    public function __construct(
        public readonly array $completedTasks,
        public readonly array $uncompletedTasks,
    ) {
    }
}
