<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class ShowTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:show {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'タスクを表示する';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $task = Task::find($this->argument('id'));

        if (is_null($task)) {
            $this->error('存在しないタスクです');

            return self::FAILURE;
        }

        $this->info(sprintf('[ID] %s', $task->id));
        $this->info(sprintf('[タイトル] %s', $task->title));
        $this->info(sprintf('[説明] %s', $task->description));
        $this->info(sprintf('[状態] %s', $task->completed ? '完了' : '未完了'));
        $this->info(sprintf('[期日] %s', $task->due_date->format('Y-m-d')));

        return self::SUCCESS;
    }
}
