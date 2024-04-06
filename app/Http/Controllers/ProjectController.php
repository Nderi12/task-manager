<?php

namespace App\Http\Controllers;

use App\Forms\Manager\ProjectForm;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with('tasks')->get();

        if ($projects->isEmpty()) {
            return response()->json(['message' => 'No projects found.'], 404);
        }

        return response()->json($projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = ProjectForm::manage($request->all());

        if (Arr::get($response, 'success')) {
            return redirect()->route('projects.index')->with('success', $response['message']);
        } else {
            return back()->with('error', implode('<br>', $response['errors']))->withInput($request->all());
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::with('tasks')->find($id);

        if (!$project) {
            return response()->json(['message' => 'No project found.'], 404);
        }

        return response()->json($project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $response = ProjectForm::manage($request->all());

        if (Arr::get($response, 'success')) {
            return redirect()->route('projects.index')->with('success', $response['message']);
        } else {
            return back()->with('error', implode('<br>', $response['errors']))->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
