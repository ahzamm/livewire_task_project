<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Middleware for authentication
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display a listing of the tasks
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->orWhere('assigned_to', Auth::id())
            ->with(['user', 'stage', 'assignee'])
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    // Show the form for creating a new task
    public function create()
    {
        $stages = Stage::all();
        $users = User::where('id', '!=', Auth::id())->where('is_admin', '!=', 1)->get(); // Exclude the current user
        return view('tasks.create', compact('stages', 'users'));
    }

    // Store a newly created task
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

    // Display the specified task
    public function show(Task $task)
    {
        // Authorization: Only the task owner or assignee can view the task
        if ($task->user_id !== Auth::id() && $task->assigned_to !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.show', compact('task'));
    }

    // Show the form for editing the specified task
    public function edit(Task $task)
    {
        // Authorization: Only the task owner or admin can edit the task
        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $stages = Stage::all();
        $users = User::where('id', '!=', Auth::id())->get(); // Exclude the current user
        return view('tasks.edit', compact('task', 'stages', 'users'));
    }

    // Update the specified task
    public function update(Request $request, Task $task)
    {
        // Authorization: Only the task owner or admin can update the task
        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stage_id' => 'required|exists:stages,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'stage_id' => $request->stage_id,
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    // Remove the specified task
    public function destroy(Task $task)
    {
        // Authorization: Only the task owner or admin can delete the task
        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}