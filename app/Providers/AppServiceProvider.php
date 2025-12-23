<?php

namespace App\Providers;

use App\Events\TaskUpdated;
use App\Listeners\NotifyProjectMembers;
use App\Models\Project;
use App\Models\Task;
use App\Observers\TaskObserver;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);

        Task::observe(TaskObserver::class);

        Event::listen(TaskUpdated::class, NotifyProjectMembers::class);
    }
}
