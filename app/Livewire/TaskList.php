<?php
namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;

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
        $tasks = Task::where(function ($query) {
            $query->where('user_id', auth()->id())->orWhere('assigned_to', auth()->id());
        })
            ->when($this->search, function ($query) {
                $query->where('title', 'like', "%{$this->search}%")->orWhere('description', 'like', "%{$this->search}%");
            })
            ->with(['user', 'stage', 'assignee'])
            ->paginate(10);

        return view('livewire.task-list', compact('tasks'));
    }
}
