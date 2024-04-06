<?php

namespace App\Http\Controllers;

use App\Forms\Manager\TaskForm;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with('project')->get();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found.'], 404);
        }

        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = TaskForm::manage($request->all());

        if (Arr::get($response, 'success')) {
            return redirect()->route('tasks.index')->with('success', $response['message']);
        } else {
            return back()->with('error', implode('<br>', $response['errors']))->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with('project')->find($id);

        if (!$task) {
            return response()->json(['message' => 'No task found.'], 404);
        }

        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $response = TaskForm::manage($request->all());

        if (Arr::get($response, 'success')) {
            return redirect()->route('tasks.index')->with('success', $response['message']);
        } else {
            return back()->with('error', implode('<br>', $response['errors']))->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if (!$task) {
            return back()->with('error', __('Task not found.'));
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
