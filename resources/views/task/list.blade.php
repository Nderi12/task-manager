@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tasks</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-end mb-3">
                        <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary mb-3">Create Task</a>
                    </div>

                    <form action="{{ route('tasks.index') }}" method="GET" class="row align-content-between">
                        @csrf
                    
                        <div class="col-4">
                            <select class="form-select" id="project-filter" name="project">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="col-4">
                            <button type="submit" class="btn btn-sm btn-primary mb-3">Filter</button>
                        </div>                    
                    </form>                    

                    @if ($tasks->isEmpty())
                        <div class="text-center">
                            <p>There are no tasks.</p>                        
                        </div>
                    @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Priority</th>
                                <th>Project</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tasks-list">
                            @foreach ($projects as $project)
                                <tr class="project-header">
                                    <td colspan="6"><strong>{{ $project->name }}</strong></td>
                                </tr>
                                @foreach ($project->tasks as $task)
                                    <tr class="task" data-task-id="{{ $task->id }}">
                                        <td><i class="fas fa-grip-vertical"></i></td>
                                        <td>{{ $task->name }}</td>
                                        <td>
                                            @if ($task->priority === 1)
                                                <span class="badge bg-success">Low</span>
                                            @elseif ($task->priority === 2)
                                                <span class="badge bg-warning">Medium</span>
                                            @else
                                                <span class="badge bg-danger">High</span>
                                            @endif
                                        </td>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $task->created_at }}</td>
                                        <td>
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this task?')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>                            
                    </table>
                    
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize SortableJS
        var tasksList = document.getElementById('tasks-list');
        var sortable = new Sortable(tasksList, {
            onUpdate: function (event) {
                console.log('Updated');
                
                // Extract the moved task ID
                var movedTaskId = event.item.dataset.taskId;
                
                // Calculate movement
                var oldIndex = event.oldIndex;
                var newIndex = event.newIndex;
                var movement = newIndex - oldIndex;

                // Send AJAX request to update priority of moved task
                fetch('{{ route("tasks.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        taskId: movedTaskId,
                        movement: movement
                    })
                })
                .then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
