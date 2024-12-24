<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use Exception;
use Illuminate\Support\Collection;

class TaskService
{
    /**
     * @return array{0: Collection<Task>, 1: Collection<Task>}
     */
    public function all(): array
    {
        $tasks = Task::all();

        $completedTasks = $tasks->filter(fn (Task $task): bool => $task->completed);
        $uncompletedTasks = $tasks->reject(fn (Task $task): bool => $task->completed);

        return [$completedTasks, $uncompletedTasks];
    }

    public function store(string $title, string $description, string $dueDate): bool
    {
        $task = new Task();
        $task->title = $title;
        $task->description = $description;
        $task->completed = false;
        $task->due_date = $dueDate;

        try {
            $task->save();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function update(int $id, string $title, string $description, bool $completed, string $dueDate): bool
    {
        // 本当はロックしたほうが安全な気がする
        $task = $this->find($id);

        $task->title = $title;
        $task->description = $description;
        $task->completed = $completed;
        $task->due_date = $dueDate;

        try {
            $task->save();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function delete(int $id): void
    {
        Task::destroy($id);
    }
}
