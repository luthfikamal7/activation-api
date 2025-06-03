@extends('layouts.app')

@section('title', 'Generate Serial Key')

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

{{-- <div class="centered-container"> --}}
    <div class="form-box">
        <h2>Generate Serial Key</h2>

        <form action="{{ route('activation.generate') }}" method="POST" class="user">
            @csrf

            <label for="duration">Duration (Months):</label>
            <select name="duration" class="form-control" required>
                <option value="">Select duration</option>
                @foreach ([3, 6, 12, 24, 36, 48, 60] as $months)
                    <option value="{{ $months }}">{{ $months }} months</option>
                @endforeach
            </select>

            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>

            <label for="project_id">Project</label>
            <select name="project_id" id="project_id" class="form-control" required>
                <option value="">Select Project</option>
            </select>

            <button type="submit" class="btn btn-primary btn-user btn-block">Generate</button>

            @if(session('success'))
                <div style="color: green; margin-top: 15px;">
                    <strong>Key generated:</strong> {{ session('success') }}
                </div>
            @endif
        </form>

        @if ($errors->any())
            <div style="color: red; margin-top: 10px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
{{-- </div> --}}

<script>
    document.getElementById('customer_id').addEventListener('change', function () {
        const customerId = this.value;
        const projectSelect = document.getElementById('project_id');

        if (!customerId) {
            projectSelect.innerHTML = '<option value="">Select Project</option>';
            return;
        }

        fetch(`/get-projects/${customerId}`)
            .then(response => response.json())
            .then(data => {
                projectSelect.innerHTML = '<option value="">Select Project</option>';
                data.forEach(project => {
                    projectSelect.innerHTML += `<option value="${project.id}">${project.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error fetching projects:', error);
            });
    });
</script>
@endsection
