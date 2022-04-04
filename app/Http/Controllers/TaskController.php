<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $tasks = Task::where('user_id', auth('sanctum')->user()->id)
            ->paginate(12);

        return response([
            'message' => 'tasks list success',
            'data' => $tasks,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TaskRequest  $request
     * @return Response
     */
    public function store(TaskRequest $request): Response
    {
        $data = $request->only(['title', 'body']);
        $data['user_id'] = auth('sanctum')->user()->id;

        $task = Task::create($data);

        return response([
            'message' => 'task created success',
            'data' => (new TaskResource($task)),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Task $task
     * @return Response
     */
    public function show(Task $task): Response
    {
        if ($this->isNotAuthorize($task)) {
            return response([
                'message' => 'Unauthorized.',
            ], 401);
        }

        return response([
            'message' => 'task get success',
            'data' => (new TaskResource($task)),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TaskRequest  $request
     * @param  Task  $task
     * @return Response
     */
    public function update(TaskRequest $request, Task $task): Response
    {
        if ($this->isNotAuthorize($task)) {
            return response([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $task->update($request->only(['title', 'body']));

        return response([
            'message' => 'task updated success',
            'data' => (new TaskResource($task)),
            'tasks' => route('api.tasks.index'),
        ], 200);
    }

    /**
     * Done Task
     *
     * @param Task $task
     * @return Response
     */
    public function doneTask(Task $task): Response
    {
        if ($this->isNotAuthorize($task)) {
            return response([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $task->update(['status' => TaskStatus::Done]);

        return response(['message' => 'task done success'], 200);
    }

    /**
     * Un Done Task
     *
     * @param Task $task
     * @return Response
     */
    public function unDoneTask(Task $task): Response
    {
        if ($this->isNotAuthorize($task)) {
            return response([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $task->update(['status' => TaskStatus::UnDone]);

        return response(['message' => 'task undone success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task  $task
     * @return Response
     */
    public function destroy(Task $task): Response
    {
        if ($this->isNotAuthorize($task)) {
            return response([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $task->delete();

        return response([
            'message' => 'task deleted success',
        ], 204);
    }

    /**
     * Check if user is onwer
     *
     * @param Task $task
     * @return bool
     */
    private function isNotAuthorize(Task $task): bool
    {
        return ($task->user_id != auth('sanctum')->user()->id);
    }
}
