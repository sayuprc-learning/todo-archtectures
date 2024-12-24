<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Support\Collection;

class TaskService
{
    public function __construct(private readonly TaskRepository $repository)
    {
    }

    /**
     * @return array{0: Collection<Task>, 1: Collection<Task>}
     */
    public function all(): array
    {
        $tasks = $this->repository->all();

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

        return $this->repository->save($task);
    }

    public function find(int $id): ?Task
    {
        return $this->repository->find($id);
    }

    public function update(int $id, string $title, string $description, bool $completed, string $dueDate): bool
    {
        // 本当はロックしたほうが安全な気がする
        $task = $this->find($id);

        $task->title = $title;
        $task->description = $description;
        $task->completed = $completed;
        $task->due_date = $dueDate;

        return $this->repository->save($task);
    }

    public function delete(int $id): void
    {
        $this->repository->destroy($id);
    }
}
