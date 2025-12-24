<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $task->title }}
            </h2>
            <div class="flex space-x-2">
                @can('update', $task)
                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                @endcan
                <a href="{{ route('projects.show', $project) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back to Project
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="px-3 py-1 text-sm rounded-full
                            @if($task->status === 'completed') bg-green-100 text-green-800
                            @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                        @if ($task->assignee)
                            <span class="text-sm text-gray-500">
                                Assigned to: <span class="font-medium">{{ $task->assignee->name }}</span>
                            </span>
                        @else
                            <span class="text-sm text-gray-400">Unassigned</span>
                        @endif
                    </div>

                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-600 mb-4">{{ $task->description ?: 'No description provided.' }}</p>

                    <div class="text-sm text-gray-400">
                        Project: <a href="{{ route('projects.show', $project) }}" class="text-blue-500 hover:underline">{{ $project->title }}</a>
                        · Created {{ $task->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Comments</h3>

                    <form method="POST" action="{{ route('projects.tasks.comments.store', [$project, $task]) }}" class="mb-6">
                        @csrf
                        <div class="mb-3">
                            <textarea name="body" rows="3" placeholder="Add a comment..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>{{ old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Add Comment
                        </button>
                    </form>

                    @if ($task->comments->isEmpty())
                        <p class="text-gray-500">No comments yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($task->comments as $comment)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="font-medium">{{ $comment->user->name }}</span>
                                            <span class="text-sm text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if ($comment->user_id === auth()->id())
                                            <form method="POST" action="{{ route('projects.tasks.comments.destroy', [$project, $task, $comment]) }}"
                                                onsubmit="return confirm('Delete this comment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-gray-700">{{ $comment->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            @if ($isOwner)
            <div class="mt-6">
                <form method="POST" action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                    onsubmit="return confirm('Are you sure you want to delete this task?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                        Delete Task
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
