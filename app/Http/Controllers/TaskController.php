<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
{
    $tasks = Task::with('user')->where('user_id', $request->user()->id)->get();
    return TaskResource::collection($tasks);
}

    public function store(StoreTaskRequest $request)
{
    $task = Task::create([
        'title' => $request->title,
        'is_completed' => false,
        'user_id' => $request->user()->id,
    ]);

    $task->load('user');

    return new TaskResource($task);
}


    public function show(Request $request, $id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
                'error' => 'task not found'
            ], 404);
        }

        $task->load('user');
        return new TaskResource($task);
    }

    public function toggle(Request $request, $id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->is_completed = !$task->is_completed;
        $task->save();

        return new TaskResource($task->load('user'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }


        // Accept partial updates: title and/or is_completed
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        // Only update provided fields
        if (array_key_exists('title', $data)) {
            $task->title = $data['title'];
        }

        if (array_key_exists('is_completed', $data)) {
            $task->is_completed = $data['is_completed'];
        }

        $task->save();

        $task->load('user');
        return new TaskResource($task);
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
