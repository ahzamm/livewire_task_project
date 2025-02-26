<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class TaskController extends Controller
{
    use WithPagination;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->orWhere('assigned_to', Auth::id())
            ->with(['user', 'stage', 'creator', 'assignee'])
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $stages = Stage::all();
        $users = User::where('id', '!=', Auth::id())->where('is_admin', '!=', 1)->get();
        return view('tasks.create', compact('stages', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stage_id' => 'required|exists:stages,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'stage_id' => $request->stage_id,
            'user_id' => Auth::id(),
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id() && $task->assigned_to !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $stages = Stage::all();
        $users = User::where('id', '!=', Auth::id())->get();
        return view('tasks.edit', compact('task', 'stages', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if (Auth::user()->is_admin) {
            $validatedData['stage_id'] = $request->validate([
                'stage_id' => 'required|exists:stages,id',
            ])['stage_id'];
        }

        $task->update($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function deleted()
    {
        Log::info('Request reached TaskController@deleted', ['user_id' => Auth::id()]);
        $tasks = Task::onlyTrashed()
            ->where(function ($query) {
                $query->where('user_id', Auth::id())->orWhereHas('creator', function ($q) {
                    $q->where('is_admin', 1);
                });
            })
            ->with(['user', 'stage', 'creator', 'assignee'])
            ->paginate(10);

        return view('tasks.deleted', compact('tasks'));
    }

    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);

        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $task->restore();

        return redirect()->route('tasks.deleted')->with('success', 'Task restored successfully.');
    }
}
