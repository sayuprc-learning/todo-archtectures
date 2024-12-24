<h1>タスク編集</h1>

<a href="{{ route('tasks.edit', $task->id) }}">タスク編集</a>

<div>
    タイトル: {{ $task->title }}<br>
    説明: {{ $task->description }}<br>
    状態: {{ $task->completed ? '完了' : '未完了' }}<br>
    期日: {{ $task->due_date->format('Y-m-d') }}
</div>
