@extends('layouts.app')

@section('title', 'List Serial Key')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Activation Key List</h2>

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

    <a href="{{ route('activation.form') }}" class="btn btn-primary mb-3">+ Add New Serial Key</a>

    @if($serialKeys->isEmpty())
        <div class="alert alert-info">No serial keys found.</div>
    @else
        <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Serial Code</th>
                        <th>Customer</th>
                        <th>Project</th>
                        {{-- <th>Project ID</th> --}}
                        <th>Start Date</th>
                        <th>Expiration Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($serialKeys as $index => $serialKey)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $serialKey->serial_code }}</td>
                        <td>{{ $serialKey->customer->name }}</td>
                        <td>{{ $serialKey->project->name  }} - {{ $serialKey->project->project_id  }}</td>
                        {{-- <td>{{ $serialKey->project->project_id  }}</td> --}}

                        @if($serialKey->is_used)
                            <td> {{ $serialKey->start_at->format('d-m-Y H:m:s') }} </td>
                            <td> {{ $serialKey->expires_at->format('d-m-Y H:m:s') }} </td>
                            <td> <button type="button" class="btn btn-success" disabled>Active</button> </td>
                        @else
                            <td>{{ 'N/A' }}</td>
                            <td>{{ 'N/A' }}</td>
                            <td><button type="button" class="btn btn-secondary" disabled>Inactive</button></td>
                        @endif
                        <td>
                            <a href="{{ route('serial.key.edit', $serialKey->id) }}" class="btn btn-info">Edit</a>
                            <form action="{{ route('serial.key.delete', $serialKey->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


</div>
@endsection


