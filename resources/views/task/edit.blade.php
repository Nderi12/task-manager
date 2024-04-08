@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Edit Task</div>

    <div class="card-body">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="text" class="form-control" name="id" value="{{ $task->id }}" hidden>
            <div class="mb-3">
                <label for="name" class="form-label">Task Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $task->name }}" required>
            </div>
            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="1" @if ($task->priority === '1') selected @endif>Low</option>
                    <option value="2" @if ($task->priority === '2') selected @endif>Medium</option>
                    <option value="3" @if ($task->priority === '3') selected @endif>High</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="project_id" class="form-label">Project</label>
                <select class="form-select" id="project_id" name="project_id" required>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @if ($task->project_id === $project->id) selected @endif>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
