<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.edit', $project) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Tasks</h3>
                        {{-- Add task button will go here later --}}
                    </div>

                    @if ($project->tasks->isEmpty())
                        <p class="text-gray-500">No tasks yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($project->tasks as $task)
                                <div class="border rounded-lg p-4 flex justify-between items-center">
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
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
