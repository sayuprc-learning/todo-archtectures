<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Task;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListTaskTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function taskList(): void
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

        $this->artisan('task:list')
            ->assertSuccessful()
            ->expectsOutput('未完了タスク')
            ->expectsOutput(sprintf('[%s] %s', 1, '未完了タスク'))
            ->expectsOutput('完了タスク')
            ->expectsOutput(sprintf('[%s] %s', 2, '完了タスク'));
    }
}
