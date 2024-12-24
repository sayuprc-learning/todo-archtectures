<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Task;
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
    public function handle(): void
    {
        $tasks = Task::all();

        $this->info('未完了タスク');

        $uncompletedTasks = $tasks->reject(fn (Task $task): bool => $task->completed);

        foreach ($uncompletedTasks as $task) {
            $this->info(sprintf('[%s] %s', $task->id, $task->title));
        }

        $this->newLine();

        $this->info('完了タスク');

        $completedTasks = $tasks->filter(fn (Task $task): bool => $task->completed);

        foreach ($completedTasks as $task) {
            $this->info(sprintf('[%s] %s', $task->id, $task->title));
        }
    }
}
