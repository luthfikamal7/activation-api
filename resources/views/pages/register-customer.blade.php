@extends('layouts.app')

@section('title', 'Register Customer')

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
    <h2>{{ isset($customer) ? 'Edit Customer' : 'Register Customer' }}</h2>
    <br>

        <form action="{{ isset($customer) ? route('customer.update', $customer->id) : route('customer.register') }}" method="POST">
            @csrf
            @if(isset($customer))
                @method('PUT')
            @endif

        <label for="customer">Customer</label>
        <input type="text" class="form-control form-control-user" id="name" name="name" 
            value="{{ old('name', $customer->name ?? '') }}"
            placeholder="Customer Name" required>

        <label for="email_label">Email</label>
        <input type="text" class="form-control form-control-user" id="email" name="email" 
            placeholder="Example: john@example.com"
            value="{{ old('email', $customer->email ?? '') }}" required
        >

        <button type="submit" class="btn btn-primary btn-user btn-block">Register</button>
    </form>
</div>

@endsection