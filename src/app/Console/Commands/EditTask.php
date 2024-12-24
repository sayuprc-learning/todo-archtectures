<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\TaskService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class EditTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:edit {id} {--title=} {--description=} {--due_date=} {--completed=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'タスクを編集する';

    /**
     * Execute the console command.
     */
    public function handle(TaskService $service): int
    {
        $arguments = $this->arguments();
        $options = $this->options();

        if ($this->isAllFalsy($options)) {
            $this->warn('更新データがありませんでした');

            return self::SUCCESS;
        }

        $argumentValidator = Validator::make($arguments, [
            'id' => ['required', 'integer'],
        ]);

        $optionValidator = Validator::make($options, [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed' => ['nullable', 'boolean'],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        if ($argumentValidator->fails() || $optionValidator->fails()) {
            $this->error('入力値が不正です');

            foreach ($argumentValidator->errors()->all() as $error) {
                $this->error($error);
            }

            foreach ($optionValidator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        // Laravel Validation の仕様上ここで空文字列じゃないことを検知する
        if (isset($options['title']) && mb_strlen(trim($options['title'])) === 0) {
            $this->error('入力値が不正です');

            $this->error('title は 1 文字以上で入力してください');

            return self::FAILURE;
        }

        $task = $service->find($arguments['id']);

        if (is_null($task)) {
            $this->error('存在しないタスクです');

            return self::FAILURE;
        }

        if (! $service->update(
            $arguments['id'],
            $options['title'] ?? $task->title,
            $options['description'] ?? $task->description,
            $options['completed'] ?? $task->completed,
            $options['due_date'] ?? $task->due_date,
        )) {
            $this->error('エラーが発生しました');

            return self::FAILURE;
        }

        $this->info('更新しました');

        return self::SUCCESS;
    }

    /**
     * オプションが指定されているかどうか
     *
     * @param array{title: ?string, description: ?string, completed: ?bool, due_date: ?string} $options
     */
    private function isAllFalsy(array $options): bool
    {
        return is_null($options['title'])
            && is_null($options['description'])
            && is_null($options['completed'])
            && is_null($options['due_date']);
    }
}
