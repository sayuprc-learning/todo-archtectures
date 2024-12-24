<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class DeleteTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'タスクを削除する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Task::destroy($this->argument('id'));

        $this->info('削除しました');
    }
}
