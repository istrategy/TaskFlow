<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        // Project owner can view any task
        if ($user->id === $task->project->owner_id) {
            return true;
        }

        // Users assigned to ANY task in this project can view all tasks
        return $task->project->tasks()->where('assigned_to', $user->id)->exists();
    }

    public function update(User $user, Task $task): bool
    {
        // Project owner can update any task
        if ($user->id === $task->project->owner_id) {
            return true;
        }

        // Assignee can update their task
        return $user->id === $task->assigned_to;
    }

    public function delete(User $user, Task $task): bool
    {
        // Only project owner can delete tasks
        return $user->id === $task->project->owner_id;
    }

    public function create(User $user, Task $task): bool
    {
        // Only project owner can create tasks
        return $user->id === $task->project->owner_id;
    }
}
