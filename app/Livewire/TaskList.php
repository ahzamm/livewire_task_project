<?php
namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskList extends Component
{
    use WithPagination;

    public string $search = '';

    #[On('search-updated')]
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Task::with(['user', 'stage', 'assignee']);

        if (!Auth::user()->is_admin) {
            $query->where(function ($q) {
                $q->where('user_id', Auth::id())->orWhere('assigned_to', Auth::id());
            });
        }

        $tasks = $query->when($this->search, function ($query) {
            $query->where('title', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        })->paginate(10);

        return view('livewire.task-list', compact('tasks'));
    }
}
