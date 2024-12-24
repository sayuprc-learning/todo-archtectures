<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use Exception;
use Illuminate\Support\Collection;

class TaskRepository
{
    /**
     * @return Collection<Task>
     */
    public function all(): Collection
    {
        return Task::all();
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function save(Task $task): bool
    {
        try {
            return $task->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function destroy(int $id): void
    {
        Task::destroy($id);
    }
}
