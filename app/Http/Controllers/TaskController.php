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
    public function index(Request $request)
    {
        $projectId = $request->query('project');

        $tasks = Task::query();
        
        if ($projectId) {
            $tasks->where('project_id', $projectId);
        }
        
        // Order tasks by lowest to highest priority
        $tasks = $tasks->orderBy('priority', 'asc')->get();

        $projects = Project::all();

        return view('task.list', [
            'tasks' => $tasks,
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::all();

        return view('task.create', [
            'projects' => $projects
        ]);        
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
        $projects = Project::all();

        return view('task.edit', [
            'task' => $task,
            'projects' => $projects
        ]);
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

    /**
     * Reorder tasks based on new priorities.
     * (Logic for reordering. If movement is a positive number, priority is increased. It is increased by the number of movements (1,2,3,4 and so on). Maximumm priority is 3
     * If movement is a negative number, priority is decreased. It is decreased by the number of movements (1,2,3,4 and so on). Minimum priority is 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        $taskId = $request->input('taskId');
        $movement = $request->input('movement');

        // Find the task
        $task = Task::findOrFail($taskId);

        // Determine the new priority based on movement direction
        $newPriority = $task->priority + $movement;

        // Ensure priority stays within the range of 1 to 3
        if ($newPriority < 1) {
            $newPriority = 1; // Minimum priority is 1
        } elseif ($newPriority > 3) {
            $newPriority = 3; // Maximum priority is 3
        }

        // Update the priority of the task
        $task->update(['priority' => $newPriority]);

        // refresh the page
        return redirect()->route('tasks.index');
    }
}
