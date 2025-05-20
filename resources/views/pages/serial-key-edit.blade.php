@extends('layouts.app')
@section('title', 'Edit Serial Key')
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
    <h2>{{ isset($serialKey) ? 'Edit Serial Key' : 'Register New Serial Key' }}</h2>

    <form action="{{ isset($serialKey) ? route('serial.key.update', $serialKey->id) : route('activation.generate') }}" method="POST">
        @csrf
        @if(isset($serialKey))
            @method('PUT')
        @endif

        <label for="serial_code">Serial Code</label>
        <input type="text" name="serial_code" value="{{ old('serial_code', $serialKey->serial_code ?? '') }}" class="form-control" readonly>

        <label for="customer">Customer</label>
        <select name="customer_id" class="form-control" required>
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ (isset($serialKey) && $serialKey->customer_id == $customer->id) ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>

        <label for="project">Project</label>
        <select name="project_id" class="form-control" required>
            <option value="">Select Project</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ (isset($serialKey) && $serialKey->project_id == $project->id) ? 'selected' : '' }}>
                    {{ $project->name }} - {{ $project->project_id }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary mt-3">
            {{ isset($serialKey) ? 'Update' : 'Register' }}
        </button>
    </form>
    
</div>

@endsection
