<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or find demo user with known credentials
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@taskflow.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create additional users
        $users = User::factory(5)->create();
        $allUsers = $users->push($demoUser);

        // Create projects owned by various users
        $projects = Project::factory(8)->recycle($allUsers)->create();

        // Create tasks for each project
        $projects->each(function ($project) use ($allUsers) {
            Task::factory(rand(3, 8))
                ->recycle($allUsers)
                ->for($project)
                ->create()
                ->each(function ($task) use ($allUsers) {
                    // Add 0-4 comments per task
                    Comment::factory(rand(0, 4))
                        ->recycle($allUsers)
                        ->for($task)
                        ->create();
                });
        });
    }
}
