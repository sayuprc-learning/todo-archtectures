<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Task;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:create {title} {description} {due_date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'タスクを作成する';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $arguments = $this->arguments();

        $validator = Validator::make($arguments, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'due_date' => ['required', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            $this->error('入力値が不正です');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $task = new Task();
        $task->title = $arguments['title'];
        $task->description = $arguments['description'];
        $task->completed = false;
        $task->due_date = $arguments['due_date'];

        try {
            $task->save();
        } catch (Exception $e) {
            $this->error('エラーが発生しました');

            return self::FAILURE;
        }

        $this->info('作成しました');

        return self::SUCCESS;
    }
}
