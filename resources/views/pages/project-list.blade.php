@extends('layouts.app')

@section('title', 'List Project')

@section('content')
<div class="container">
    <h2 class="mb-4">Project List</h2>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">
            <strong>Status:</strong> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
            <div style="color: red; margin-top: 15px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif

    <a href="{{ route('project.register.form') }}" class="btn btn-primary mb-3">+ Add New Project</a>

    @if($projects->isEmpty())
        <div class="alert alert-info">No projects found.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable">
            <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Project Name</th>
                        <th>Project ID</th>
                        <th>Customer</th>
                        <th>Actions</th>
                    </tr>
            </thead>
            <tbody>
                @foreach($projects as $index => $project)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->project_id }}</td>
                        <td>{{ $project->customer->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('project.edit', $project->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('project.delete', $project->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @endif

</div>
@endsection


