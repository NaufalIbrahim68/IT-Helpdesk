@extends('layouts.auth')

@section('content')
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center" style="background-color: #f8f9fa;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 420px; border-radius: 15px;">
        <div class="card-body p-5">

            {{-- Logo --}}
            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/AVI.png') }}" alt="Astra Visteon Indonesia" style="height: 80px;">
            </div>

            {{-- Error Message --}}
            @if($errors->has('login'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first('login') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="npk">NPK</label>
                <input type="text" name="npk" id="npk" required class="form-control mb-3">

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required class="form-control mb-4">

                {{-- Optional Role Selector (hapus kalau tidak dibutuhkan) --}}
                {{-- 
                <label for="role">Login Sebagai</label>
                <select name="role" id="role" class="form-select mb-3">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select> 
                --}}

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

        </div>
    </div>
</div>

{{-- Styling tambahan --}}
<style>
    .form-control:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4) !important;
    }

    .card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    body {
        font-family: 'Nunito', sans-serif;
    }
</style>
@endsection
