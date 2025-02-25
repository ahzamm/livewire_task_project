<!-- resources/views/livewire/task-actions.blade.php -->
<div>
    <a href="{{ route('tasks.edit', $row->id) }}" class="btn btn-sm btn-primary">Edit</a>
    <button wire:click="deleteTask({{ $row->id }})" class="btn btn-sm btn-danger">Delete</button>
</div>