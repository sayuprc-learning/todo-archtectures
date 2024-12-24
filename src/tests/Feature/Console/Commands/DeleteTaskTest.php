<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Task;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function deleteTask(): void
    {
        $task = new Task();
        $task->title = 'タスク';
        $task->description = 'タスク説明';
        $task->completed = false;
        $task->due_date = (new DateTimeImmutable())->modify('+180 days')->format('Y-m-d');
        $task->save();

        $this->artisan('task:delete', ['id' => 1])
            ->assertSuccessful()
            ->expectsOutput('削除しました');

        $this->assertDatabaseEmpty(Task::class);
    }
}
