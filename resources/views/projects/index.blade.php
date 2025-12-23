<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                New Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($projects->isEmpty())
                        <p class="text-gray-500">No projects yet. Create your first project!</p>
                    @else
                        <div class="grid gap-4">
                            @foreach ($projects as $project)
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('projects.show', $project) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                                                {{ $project->title }}
                                            </a>
                                            <p class="text-gray-600 mt-1">{{ Str::limit($project->description, 100) }}</p>
                                            <p class="text-sm text-gray-400 mt-2">
                                                {{ $project->tasks_count ?? $project->tasks->count() }} tasks · Created {{ $project->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('projects.edit', $project) }}" class="text-gray-500 hover:text-blue-600">
                                                Edit
                                            </a>
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-600">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
