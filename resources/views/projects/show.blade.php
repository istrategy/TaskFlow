<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->title }}
            </h2>
            <div class="flex space-x-2">
                @if ($isOwner)
                <a href="{{ route('projects.edit', $project) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                @endif
                <a href="{{ route('projects.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-600">{{ $project->description ?: 'No description provided.' }}</p>
                    <div class="mt-4 text-sm text-gray-400">
                        Owner: {{ $project->owner->name }} · Created {{ $project->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Task Statistics</h3>
                    <div style="display: flex; gap: 2rem; margin-bottom: 1rem;">
                        <div style="min-width: 80px;">
                            <div class="text-sm text-gray-500 mb-1">Total</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $taskStats['total'] }}</div>
                        </div>
                        <div style="min-width: 80px;">
                            <div class="text-sm text-gray-500 mb-1">Completed</div>
                            <div class="text-2xl font-bold text-green-600">{{ $taskStats['completed'] }}</div>
                        </div>
                        <div style="min-width: 80px;">
                            <div class="text-sm text-gray-500 mb-1">In Progress</div>
                            <div class="text-2xl font-bold text-yellow-600">{{ $taskStats['in_progress'] }}</div>
                        </div>
                        <div style="min-width: 80px;">
                            <div class="text-sm text-gray-500 mb-1">Pending</div>
                            <div class="text-2xl font-bold text-gray-600">{{ $taskStats['pending'] }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="flex: 1; background-color: #e5e7eb; border-radius: 9999px; height: 0.5rem; overflow: hidden;">
                            <div style="background-color: #22c55e; height: 0.5rem; border-radius: 9999px; width: {{ $taskStats['completion_percentage'] }}%;"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-600" style="white-space: nowrap;">{{ $taskStats['completion_percentage'] }}% Complete</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Tasks</h3>
                        @if ($isOwner)
                        <a href="{{ route('projects.tasks.create', $project) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Add Task
                        </a>
                        @endif
                    </div>

                    @if ($project->tasks->isEmpty())
                        <p class="text-gray-500">No tasks yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($project->tasks as $task)
                                <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                    class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50 block">
                                    <div>
                                        <span class="font-medium">{{ $task->title }}</span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded 
                                            @if($task->status === 'completed') bg-green-100 text-green-800
                                            @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                        @if ($task->assignee)
                                            <span class="ml-2 text-sm text-gray-500">
                                                → {{ $task->assignee->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-gray-400 text-sm">{{ $task->comments->count() }} comments</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
