<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;

class TaskList extends Component
{
    use WithPagination; // Enable pagination

    public $search = ''; // For search functionality

    public function render()
    {
        // Fetch tasks for the logged-in user (owner or assignee)
        $tasks = Task::where('user_id', auth()->id())
            ->orWhere('assigned_to', auth()->id())
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->with(['user', 'stage', 'assignee'])
            ->paginate(10); // Paginate results

        return view('livewire.task-list', [
            'tasks' => $tasks,
        ]);
    }
}