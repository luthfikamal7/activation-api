@extends('layouts.app')

@section('title', 'List Customer')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Customer List</h2>
        
    </div>
    @if(session('success'))
            <div style="color: green; margin-top: 15px;">
                <strong>Status :</strong> {{ session('success') }}
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

    <a href="{{ route('customer.register.form') }}" class="btn btn-primary mb-3">+ Register New Customer</a>

    @if($customers->isEmpty())
        <div class="alert alert-info">No customers found.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>@if($customer->created_at)
                                {{ $customer->created_at->format('d-m-Y H:m:s') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('customer.delete', $customer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure to delete this customer?')" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
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
