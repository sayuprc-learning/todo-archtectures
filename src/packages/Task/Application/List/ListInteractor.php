<?php

declare(strict_types=1);

namespace Task\Application\List;

use Task\Domain\Task;
use Task\Domain\TaskRepositoryInterface;
use Task\UseCase\List\ListRequest;
use Task\UseCase\List\ListResponse;
use Task\UseCase\List\ListUseCaseInterface;

class ListInteractor implements ListUseCaseInterface
{
    public function __construct(private readonly TaskRepositoryInterface $repository)
    {
    }

    public function handle(ListRequest $request): ListResponse
    {
        $tasks = $this->repository->all();

        $completedTasks = array_filter($tasks, fn (Task $task): bool => $task->isCompleted());
        $uncompletedTasks = array_filter($tasks, fn (Task $task): bool => ! $task->isCompleted());

        return new ListResponse($completedTasks, $uncompletedTasks);
    }
}
