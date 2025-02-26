@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-4">{{ $task->title }}</h1>
                <p class="mb-4 text-gray-700 dark:text-gray-300">{{ $task->description }}</p>

                <p class="mb-4"><strong>Stage:</strong> {{ $task->stage->name }}</p>
                <p class="mb-4"><strong>Assigned To:</strong> {{ $task->assignee->name ?? 'Unassigned' }}</p>

                <a href="{{ route('tasks.index') }}" class="text-blue-500 hover:text-blue-700">Back to Tasks</a>
            </div>
        </div>
    </div>
</div>
@endsection
