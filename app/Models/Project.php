<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Many-to-many relationship with users (project members).
     */
    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    /**
     * Check if a user is a member of this project.
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Add a user as a member of this project.
     */
    public function addMember(User $user): void
    {
        if (!$this->hasMember($user)) {
            $this->members()->attach($user->id);
        }
    }

    /**
     * Remove a user from this project's members.
     */
    public function removeMember(User $user): void
    {
        $this->members()->detach($user->id);
    }
}
