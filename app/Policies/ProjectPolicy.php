<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        // Owner can view
        if ($user->id === $project->owner_id) {
            return true;
        }

        // Members can view
        if ($project->hasMember($user)) {
            return true;
        }

        // Workers with assigned tasks can view
        return $project->tasks()->where('assigned_to', $user->id)->exists();
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }

    /**
     * Determine if the user can manage project members.
     */
    public function manageMembers(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }
}
