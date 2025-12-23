<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskHistory;

class TaskObserver
{
    public function created(Task $task): void
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'event_type' => 'created',
            'old_values' => null,
            'new_values' => $task->toArray(),
        ]);
    }

    public function updated(Task $task): void
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'event_type' => 'updated',
            'old_values' => $task->getOriginal(),
            'new_values' => $task->getChanges(),
        ]);
    }

    public function deleted(Task $task): void
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'event_type' => 'deleted',
            'old_values' => $task->toArray(),
            'new_values' => null,
        ]);
    }
}
