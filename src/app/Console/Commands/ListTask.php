<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\TaskService;
use Illuminate\Console\Command;

class ListTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'タスクの一覧を表示する';

    /**
     * Execute the console command.
     */
    public function handle(TaskService $service): void
    {
        [$completedTasks, $uncompletedTasks] = $service->all();

        $this->info('未完了タスク');

        foreach ($uncompletedTasks as $task) {
            $this->info(sprintf('[%s] %s', $task->id, $task->title));
        }

        $this->newLine();

        $this->info('完了タスク');

        foreach ($completedTasks as $task) {
            $this->info(sprintf('[%s] %s', $task->id, $task->title));
        }
    }
}
