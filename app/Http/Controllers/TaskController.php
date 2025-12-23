<?php

namespace App\Http\Controllers;

use App\Events\TaskUpdated;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function create(Project $project)
    {
        $this->authorize('update', $project);
        $users = User::all();
        return view('tasks.create', compact('project', 'users'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['project_id'] = $project->id;

        Task::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Task created successfully.');
    }

    public function show(Project $project, Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['assignee', 'comments.user']);
        $isOwner = Auth::id() === $project->owner_id;
        return view('tasks.show', compact('project', 'task', 'isOwner'));
    }

    public function edit(Project $project, Task $task)
    {
        $this->authorize('update', $task);
        $users = User::all();
        $isOwner = Auth::id() === $project->owner_id;
        return view('tasks.edit', compact('project', 'task', 'users', 'isOwner'));
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $task);

        $isOwner = Auth::id() === $project->owner_id;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => $isOwner ? 'nullable|exists:users,id' : 'prohibited',
        ]);

        // Assignees cannot change assignment
        if (!$isOwner) {
            unset($validated['assigned_to']);
        }

        $task->update($validated);

        Log::info('TaskController::update - dispatching TaskUpdated event', ['task_id' => $task->id]);
        TaskUpdated::dispatch($task);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Task deleted successfully.');
    }
}
