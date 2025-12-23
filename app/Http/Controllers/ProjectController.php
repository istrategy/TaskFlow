<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $ownedProjects = Auth::user()->projects()->pluck('id');
        $assignedProjects = Project::whereHas('tasks', function ($query) {
            $query->where('assigned_to', Auth::id());
        })->pluck('id');

        $projects = Project::whereIn('id', $ownedProjects->merge($assignedProjects))
            ->with('owner')
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['owner_id'] = Auth::id();

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load(['tasks.assignee', 'tasks.comments', 'owner']);
        $isOwner = Auth::id() === $project->owner_id;

        // Task statistics using Laravel Collections
        $tasks = $project->tasks;
        $taskStats = [
            'total' => $tasks->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'completion_percentage' => $tasks->count() > 0 
                ? round(($tasks->where('status', 'completed')->count() / $tasks->count()) * 100) 
                : 0,
        ];

        return view('projects.show', compact('project', 'isOwner', 'taskStats'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
