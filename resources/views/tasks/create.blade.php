<!-- resources/views/tasks/create.blade.php -->
<!-- resources/views/tasks/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($task) ? 'Edit Task' : 'Create Task' }}</h1>
    <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
        @csrf
        @if (isset($task))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ isset($task) ? $task->title : old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ isset($task) ? $task->description : old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="stage_id">Stage</label>
            <select name="stage_id" id="stage_id" class="form-control" required>
                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}" {{ (isset($task) && $task->stage_id === $stage->id) ? 'selected' : '' }}>{{ $stage->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="assigned_to">Assign To</label>
            <select name="assigned_to" id="assigned_to" class="form-control">
                <option value="">Unassigned</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ (isset($task) && $task->assigned_to === $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($task) ? 'Update' : 'Create' }} Task</button>
    </form>
</div>
@endsection