<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Task;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EditTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function editTask(): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->artisan('task:edit', [
            'id' => 1,
            '--title' => '完了タスク',
            '--description' => '説明',
            'completed' => true,
            '--due_date' => (new DateTimeImmutable())->modify('+60 days')->format('Y-m-d'),
        ])->assertSuccessful()
            ->expectsOutput('更新しました');

        $tasks = Task::all();

        $this->assertCount(1, $tasks);
        $task = $tasks->first();
        $this->assertSame('完了タスク', $task->title);
        $this->assertSame('説明', $task->description);
        $this->assertSame(true, $task->completed);
        $this->assertSame((new DateTimeImmutable())->modify('+60 days')->format('Y-m-d'), $task->due_date->format('Y-m-d'));
    }

    #[Test]
    public function editTaskNotFound(): void
    {
        $this->artisan('task:edit', [
            'id' => 1,
            '--completed' => true,
        ])->assertFailed()
            ->expectsOutput('存在しないタスクです');
    }

    #[Test]
    #[DataProvider('editTaskFieldProvider')]
    public function editTaskFailed(array $items, array $outputs): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $result = $this->artisan('task:edit', $items)
            ->assertFailed();

        foreach ($outputs as $output) {
            $result->expectsOutput($output);
        }
    }

    public static function editTaskFieldProvider(): array
    {
        return [
            [
                'items' => [
                    'id' => 1,
                    '--title' => '',
                    '--description' => 'task description',
                    '--due_date' => '2024-12-01',
                ],
                'outputs' => [
                    '入力値が不正です',
                    'title は 1 文字以上で入力してください',
                ],
            ],
            [
                'items' => [
                    'id' => 1,
                    '--title' => str_repeat('a', 256),
                    '--description' => 'task description',
                    '--due_date' => '2024/12/01',
                ],
                'outputs' => [
                    '入力値が不正です',
                    'The title field must not be greater than 255 characters.',
                    'The due date field must match the format Y-m-d.',
                ],
            ],
        ];
    }
}
