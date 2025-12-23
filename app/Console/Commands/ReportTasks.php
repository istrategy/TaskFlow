<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class ReportTasks extends Command
{
    protected $signature = 'report:tasks';

    protected $description = 'Display a project summary table with task statistics';

    public function handle(): int
    {
        $projects = Project::with('tasks')->get();

        if ($projects->isEmpty()) {
            $this->info('No projects found.');
            return Command::SUCCESS;
        }

        $rows = $projects->map(function ($project) {
            $tasks = $project->tasks;
            $total = $tasks->count();
            $completed = $tasks->where('status', 'completed')->count();
            $pending = $tasks->where('status', 'pending')->count();
            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

            return [
                $project->title,
                $total,
                $completed,
                $pending,
                $percentage . '%',
            ];
        });

        $this->table(
            ['Project', 'Total Tasks', 'Completed', 'Pending', 'Completion %'],
            $rows
        );

        return Command::SUCCESS;
    }
}
