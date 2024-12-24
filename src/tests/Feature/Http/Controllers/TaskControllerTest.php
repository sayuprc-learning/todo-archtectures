<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function index(): void
    {
        $uncompletedTask = new Task();
        $uncompletedTask->title = '未完了タスク';
        $uncompletedTask->description = '未完了タスク説明';
        $uncompletedTask->completed = false;
        $uncompletedTask->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $uncompletedTask->save();

        $completedTask = new Task();
        $completedTask->title = '完了タスク';
        $completedTask->description = '完了タスク説明';
        $completedTask->completed = true;
        $completedTask->due_date = (new DateTimeImmutable())->modify('+60 days')->format('Y-m-d');
        $completedTask->save();

        $this->get(route('tasks.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function create(): void
    {
        $this->get(route('tasks.create'))
            ->assertStatus(200);
    }

    #[Test]
    public function store(): void
    {
        $this->post(
            route('tasks.store'),
            [
                'title' => 'タスク作成',
                'description' => '説明',
                'due_date' => (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d'),
            ]
        )->assertStatus(302)
            ->assertRedirect(route('tasks.index'))
            ->assertSessionHas('message', '作成しました');

        $tasks = Task::all();

        $this->assertCount(1, $tasks);
        $task = $tasks->first();
        $this->assertSame('タスク作成', $task->title);
        $this->assertSame('説明', $task->description);
        $this->assertSame(false, $task->completed);
        $this->assertSame((new DateTimeImmutable())->modify('+180 days')->format('Y-m-d'), $task->due_date->format('Y-m-d'));
    }

    #[Test]
    #[DataProvider('storeFailedProvider')]
    public function storeFailed(array $items, array $errors): void
    {
        $this->post(route('tasks.store'), $items)
            ->assertStatus(302)
            ->assertSessionHasErrors($errors);
    }

    public static function storeFailedProvider(): array
    {
        return [
            [
                'items' => [],
                'errors' => [
                    'title' => 'The title field is required.',
                    'description' => 'The description field is required.',
                    'due_date' => 'The due date field is required.',
                ],
            ],
            [
                'items' => [
                    'title' => str_repeat('a', 256),
                    'description' => 'task description',
                    'due_date' => '2024/12/01',
                ],
                'errors' => [
                    'title' => 'The title field must not be greater than 255 characters.',
                    'due_date' => 'The due date field must match the format Y-m-d.',
                ],
            ],
        ];
    }

    #[Test]
    public function show(): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->get(route('tasks.show', ['id' => 1]))
            ->assertStatus(200);
    }

    #[Test]
    public function showNotFound(): void
    {
        $this->get(route('tasks.show', ['id' => 1]))
            ->assertStatus(302)
            ->assertSessionHasErrors(['message' => '存在しないタスクです']);
    }

    #[Test]
    public function edit(): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->get(route('tasks.edit', ['id' => 1]))
            ->assertStatus(200);
    }

    #[Test]
    public function editNotFound(): void
    {
        $this->get(route('tasks.edit', ['id' => 1]))
            ->assertStatus(302)
            ->assertSessionHasErrors(['message' => '存在しないタスクです']);
    }

    #[Test]
    public function update(): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->patch(
            route('tasks.update', ['id' => 1]),
            [
                'title' => 'タイトル',
                'description' => '詳細',
                'completed' => true,
                'due_date' => (new DateTimeImmutable())->modify('+60 days')->format('Y-m-d'),
            ]
        )->assertStatus(302)
            ->assertRedirect(route('tasks.index'))
            ->assertSessionHas('message', '更新しました');

        $tasks = Task::all();

        $this->assertCount(1, $tasks);
        $task = $tasks->first();
        $this->assertSame('タイトル', $task->title);
        $this->assertSame('詳細', $task->description);
        $this->assertSame(true, $task->completed);
        $this->assertSame((new DateTimeImmutable())->modify('+60 days')->format('Y-m-d'), $task->due_date->format('Y-m-d'));
    }

    #[Test]
    public function updateNotFound(): void
    {
        $this->get(route('tasks.update', ['id' => 1]))
            ->assertStatus(302)
            ->assertSessionHasErrors(['message' => '存在しないタスクです']);
    }

    #[Test]
    #[DataProvider('updateFailedProvider')]
    public function updateFailed(array $items, array $errors): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->patch(route('tasks.update', ['id' => 1]), $items)
            ->assertStatus(302)
            ->assertSessionHasErrors($errors);

        // 変わってないことをテスト
        $tasks = Task::all();

        $this->assertCount(1, $tasks);
        $task = $tasks->first();
        $this->assertSame('タスク', $task->title);
        $this->assertSame('タスク説明', $task->description);
        $this->assertSame(false, $task->completed);
        $this->assertSame((new DateTimeImmutable())->modify('+180 days')->format('Y-m-d'), $task->due_date->format('Y-m-d'));
    }

    public static function updateFailedProvider(): array
    {
        return [
            [
                'items' => [],
                'errors' => [
                    'title' => 'The title field is required.',
                    'description' => 'The description field is required.',
                    'due_date' => 'The due date field is required.',
                ],
            ],
            [
                'items' => [
                    'title' => str_repeat('a', 256),
                    'description' => 'task description',
                    'due_date' => '2024/12/01',
                ],
                'errors' => [
                    'title' => 'The title field must not be greater than 255 characters.',
                    'due_date' => 'The due date field must match the format Y-m-d.',
                ],
            ],
        ];
    }

    #[Test]
    public function destroy(): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->delete(route('tasks.destroy', ['id' => 1]))
            ->assertStatus(302)
            ->assertRedirect(route('tasks.index'))
            ->assertSessionHas('message', '削除しました');

        $this->assertDatabaseEmpty(Task::class);
    }
}
