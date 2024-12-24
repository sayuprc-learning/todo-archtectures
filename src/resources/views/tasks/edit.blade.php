<h1>タスク更新</h1>

@if ($errors->any())
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('tasks.update', $task->id) }}" method="post">
    @csrf
    @method('PATCH')
    <label for="title">タイトル</label>
    <input id="title" name="title" type="text" value="{{ old('title', $task->title) }}"><br>
    <label for="description">説明</label>
    <input id="description" name="description" type="text" value="{{ old('description', $task->description) }}"><br>
    <label for="completed">状態</label>
    <input id="completed" name="completed" type="checkbox" value="1" {{ $task->completed ? 'checked' : '' }}"><br>
    <label for="due_date">期日</label>
    <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}"><br>
    <input type="submit" value="更新">
</form>

<form action="{{ route('tasks.destroy', $task->id) }}" method="post">
    @csrf
    @method('DELETE')
    <input type="submit" value="削除">
</form>
