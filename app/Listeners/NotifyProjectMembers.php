<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Notifications\TaskUpdatedNotification;
use Illuminate\Support\Facades\Log;

class NotifyProjectMembers
{
    private static array $processedTasks = [];

    public function __construct()
    {
        //
    }

    public function handle(TaskUpdated $event): void
    {
        $task = $event->task;

        // Prevent duplicate notifications (listener may be registered twice)
        $key = $task->id . '-' . $task->updated_at->timestamp;
        if (in_array($key, self::$processedTasks)) {
            Log::info('NotifyProjectMembers::handle - skipping duplicate', ['task_id' => $task->id]);
            return;
        }
        self::$processedTasks[] = $key;

        Log::info('NotifyProjectMembers::handle called', ['task_id' => $task->id]);
        $project = $task->project;
        $owner = $project->owner;
        $assignee = $task->assignee;

        $notifiedUserIds = [];

        // Notify project owner
        if ($owner) {
            $owner->notify(new TaskUpdatedNotification($task));
            $notifiedUserIds[] = $owner->id;
        }

        // Notify task assignee (if different from owner)
        if ($assignee && !in_array($assignee->id, $notifiedUserIds)) {
            $assignee->notify(new TaskUpdatedNotification($task));
        }
    }
}
