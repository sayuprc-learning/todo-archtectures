<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $service)
    {
    }

    /**
     * タスクの一覧表示メソッド
     * 未完了のタスクと完了済みのタスクは分けて表示する
     */
    public function index(): View
    {
        [$completedTasks , $uncompletedTasks] = $this->service->all();

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

        if (! $this->service->store(
            $request->input('title'),
            $request->input('description'),
            $request->input('due_date'),
        )) {
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
        $task = $this->service->find($id);

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
        $task = $this->service->find($id);

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

        $task = $this->service->find($id);

        if (is_null($task)) {
            return redirect()
                ->route('tasks.index')
                ->withErrors(['message' => '存在しないタスクです']);
        }

        if (! $this->service->update(
            $id,
            $request->input('title'),
            $request->input('description'),
            $request->input('completed', false),
            $request->input('due_date'),
        )) {
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
        $this->service->delete($id);

        return redirect()
            ->route('tasks.index')
            ->with('message', '削除しました');
    }
}
