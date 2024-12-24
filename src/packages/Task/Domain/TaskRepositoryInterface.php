<?php

declare(strict_types=1);

namespace Task\Domain;

interface TaskRepositoryInterface
{
    /**
     * @return array<Task>
     */
    public function all(): array;

    public function find(Id $id): ?Task;

    public function save(Task $task): bool;

    public function delete(Id $id): bool;
}
