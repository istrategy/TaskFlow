<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['task_id'] = $task->id;

        Comment::create($validated);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Comment added successfully.');
    }

    public function destroy(Project $project, Task $task, Comment $comment)
    {
        $this->authorize('view', $task);

        if ($comment->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own comments.');
        }

        $comment->delete();

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Comment deleted successfully.');
    }
}
