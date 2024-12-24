<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Task;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function createTask(): void
    {
        $this->artisan('task:create', [
            'title' => '未完了タスク',
            'description' => '説明',
            'due_date' => (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d'),
        ])->assertSuccessful()
            ->expectsOutput('作成しました');

        $tasks = Task::all();

        $this->assertCount(1, $tasks);
        $task = $tasks->first();
        $this->assertSame('未完了タスク', $task->title);
        $this->assertSame('説明', $task->description);
        $this->assertSame(false, $task->completed);
        $this->assertSame((new DateTimeImmutable())->modify('+180 days')->format('Y-m-d'), $task->due_date->format('Y-m-d'));
    }

    #[Test]
    #[DataProvider('createTaskFieldProvider')]
    public function createTaskFailed(array $items, array $outputs): void
    {
        $result = $this->artisan('task:create', $items)
            ->assertFailed();

        foreach ($outputs as $output) {
            $result->expectsOutput($output);
        }
    }

    public static function createTaskFieldProvider(): array
    {
        return [
            [
                'items' => [
                    'title' => str_repeat('a', 256),
                    'description' => 'task description',
                    'due_date' => '2024/12/01',
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
