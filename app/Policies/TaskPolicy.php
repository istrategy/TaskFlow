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

        // Assignee can view their task
        return $user->id === $task->assigned_to;
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
