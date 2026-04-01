@extends('layouts.app')
@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f1f3f5;
        }

        .register-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 60px;
        }

        .register-card h2 {
            font-size: 22px;
            font-weight: bold;
            color: #198754;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-label {
            font-weight: 600;
            color: #343a40;
        }

        .btn-primary {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-primary:hover {
            background-color: #157347;
            border-color: #157347;
        }

        .login-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #198754;
            font-weight: 500;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
            color: #157347;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="register-card">
                    <h2> Register </h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="*">Role</option>
                                <option value="doctor">Doctor</option>
                                <option value="nurse">Nurse</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="password" class="form-label">Password </label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label"> Confirm password </label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-user-plus me-2"></i> Register
                            </button>
                        </div>

                        <a href="{{ route('login') }}" class="login-link">
                            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Have Account?Login
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
