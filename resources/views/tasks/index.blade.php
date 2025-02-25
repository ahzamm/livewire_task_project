<!-- resources/views/tasks/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Create Task</a>

    <!-- Livewire Task Table -->
    <livewire:task-list />
</div>
@endsection