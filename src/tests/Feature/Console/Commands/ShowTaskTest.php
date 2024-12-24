<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Task;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowTaskTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function showTask(): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->artisan('task:show', ['id' => $task->id])
            ->assertSuccessful()
            ->expectsOutput(sprintf('[ID] %s', $task->id))
            ->expectsOutput(sprintf('[タイトル] %s', $task->title))
            ->expectsOutput(sprintf('[説明] %s', $task->description))
            ->expectsOutput(sprintf('[状態] %s', $task->completed ? '完了' : '未完了'))
            ->expectsOutput(sprintf('[期日] %s', $task->due_date->format('Y-m-d')));
    }

    #[Test]
    public function showTaskNotFound(): void
    {
        $this->artisan('task:show', ['id' => 1])
            ->assertFailed()
            ->expectsOutput('存在しないタスクです');
    }
}
