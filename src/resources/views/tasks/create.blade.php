<h1>タスク作成</h1>

@if ($errors->any())
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('tasks.store') }}" method="post">
    @csrf
    <label for="title">タイトル</label>
    <input id="title" name="title" type="text" value="{{ old('title') }}"><br>
    <label for="description">説明</label>
    <input id="description" name="description" type="text" value="{{ old('description') }}"><br>
    <label for="due_date">期日</label>
    <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}"><br>
    <input type="submit" value="作成">
</form>
