<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->title }}
            </h2>
            <div class="flex space-x-2">
                @if ($isOwner)
                    <a href="{{ route('projects.edit', $project) }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                <a href="{{ route('projects.index') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

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
                        <div
                            style="flex: 1; background-color: #e5e7eb; border-radius: 9999px; height: 0.5rem; overflow: hidden;">
                            <div
                                style="background-color: #22c55e; height: 0.5rem; border-radius: 9999px; width: {{ $taskStats['completion_percentage'] }}%;">
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-600"
                            style="white-space: nowrap;">{{ $taskStats['completion_percentage'] }}% Complete</span>
                    </div>
                </div>
            </div>

            @if ($isOwner)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Project Members</h3>

                        <!-- Add Member Form -->
                        <form action="{{ route('projects.members.add', $project) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex gap-2">
                                <select name="user_id"
                                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    <option value="">Select a user to add...</option>
                                    @php
                                        $memberIds = $project->members->pluck('id')->push($project->owner_id);
                                        $availableUsers = \App\Models\User::whereNotIn('id', $memberIds)->get();
                                    @endphp
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Add Member
                                </button>
                            </div>
                        </form>

                        <!-- Members List -->
                        <div class="space-y-2">
                            <!-- Owner -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($project->owner->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $project->owner->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $project->owner->email }}</div>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Owner</span>
                            </div>

                            <!-- Members -->
                            @foreach($project->members as $member)
                                <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                    <form action="{{ route('projects.members.remove', [$project, $member]) }}" method="POST"
                                        onsubmit="return confirm('Remove {{ $member->name }} from this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            @endforeach

                            @if($project->members->isEmpty())
                                <p class="text-gray-500 text-sm italic py-2">No additional members yet. Add members to give them
                                    access to this project.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

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