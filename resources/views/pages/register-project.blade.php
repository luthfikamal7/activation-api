@extends('layouts.app')

@section('title', 'Register Project')

@section('content')

<style>
    .centered-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh;
        text-align: center;
    }

    .form-box {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .form-box form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
</style>

<div class="form-box">
    <h2>{{ isset($project) ? 'Edit Project' : 'Register New Project' }}</h2>

    <form action="{{ isset($project) ? route('project.update', $project->id) : route('project.register') }}" method="POST">
        @csrf
        @if(isset($project))
            @method('PUT')
        @endif

        <label for="name">Project Name</label>
        <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}" class="form-control" required>

        <label for="project_id">Project ID</label>
        <input type="text" name="project_id" value="{{ old('project_id', $project->project_id ?? '') }}" class="form-control" required>

        <label for="customer_id">Customer</label>
        <select name="customer_id" class="form-control" required>
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ (isset($project) && $project->customer_id == $customer->id) ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary mt-3">
            {{ isset($project) ? 'Update' : 'Register' }}
        </button>
    </form>
    
</div>

@endsection