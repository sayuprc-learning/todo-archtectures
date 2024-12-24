<a href="{{ route('tasks.create') }}">タスク作成</a><br>

<div>
    @if (session('message'))
        {{ session('message') }}
    @endif
</div>

<div>
    @if ($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>

<h2>未完了タスク一覧</h2>
<ul>
    @foreach($uncompletedTasks as $task)
        <li>
            <a href="{{ route('tasks.show', $task->id) }}" style="{{ $task->due_date->format('Y-m-d') < (new DateTime())->format('Y-m-d') ? 'color: red;' : '' }}">
                {{ $task->title }}
            </a>
        </li>
    @endforeach
</ul>
<h2>完了タスク</h2>
<ul>
    @foreach($completedTasks as $task)
        <li><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a></li>
    @endforeach
</ul>
