@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Edit Project</div>

    <div class="card-body">
        <form action="{{ route('projects.update', $project->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Project Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $project->name }}" required>
                <input type="text" name="id" value="{{ $project->id }}" hidden>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
