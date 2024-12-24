<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * タスクの一覧表示メソッド
     * 未完了のタスクと完了済みのタスクは分けて表示する
     */
    public function index(): View
    {
        $tasks = Task::all();

        $completedTasks = $tasks->filter(fn (Task $task): bool => $task->completed);
        $uncompletedTasks = $tasks->reject(fn (Task $task): bool => $task->completed);

        return view('tasks.index', compact('completedTasks', 'uncompletedTasks'));
    }

    /**
     * タスクの作成画面表示メソッド
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * タスクの作成メソッド
     * 作成したら一覧画面にリダイレクトする
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string'],
            'due_date' => ['required', 'date_format:Y-m-d'],
        ]);

        $task = new Task();
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->completed = false;
        $task->due_date = $request->input('due_date');

        try {
            $task->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()
                ->route('tasks.index')
                ->withErrors(['message' => 'エラーが発生しました']);
        }

        return redirect()
            ->route('tasks.index')
            ->with('message', '作成しました');
    }

    /**
     * タスクの ID をもとに詳細画面を表示するメソッド
     */
    public function show(int $id): RedirectResponse|View
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return redirect()
                ->route('tasks.index')
                ->withErrors(['message' => '存在しないタスクです']);
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * タスクの ID をもとに編集画面を表示するメソッド
     */
    public function edit(int $id): RedirectResponse|View
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return redirect()
                ->route('tasks.index')
                ->withErrors(['message' => '存在しないタスクです']);
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * 対象のタスクを更新するメソッド
     * 更新したら一覧画面にリダイレクトする
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string'],
            // チェックされない場合はとってこれないので required にはしない
            'completed' => ['boolean'],
            'due_date' => ['required', 'date_format:Y-m-d'],
        ]);

        $task = Task::find($id);

        if (is_null($task)) {
            return redirect()
                ->route('tasks.index')
                ->withErrors(['message' => '存在しないタスクです']);
        }

        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->completed = $request->input('completed', false);
        $task->due_date = $request->input('due_date');

        try {
            $task->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()
                ->route('tasks.index')
                ->withErrors(['message' => 'エラーが発生しました']);
        }

        return redirect()
            ->route('tasks.index')
            ->with('message', '更新しました');
    }

    /**
     * 対象のタスクを削除するメソッド
     * 削除したら一覧画面にリダイレクトする
     */
    public function destroy(int $id): RedirectResponse
    {
        Task::destroy($id);

        return redirect()
            ->route('tasks.index')
            ->with('message', '削除しました');
    }
}
