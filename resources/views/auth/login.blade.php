@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .login-card {
            background-color: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-top: 50px;
            transition: transform 0.3s ease;
        }

        .login-card h2 {
            font-size: 26px;
            font-weight: 700;
            color: #198754;
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 1px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #198754;
        }

        .form-control,
        .form-select {
            border-left: none;
            padding: 12px;
            border-color: #dee2e6;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #198754;
        }

        .btn-primary {
            background-color: #198754;
            border: none;
            padding: 12px;
            font-weight: 700;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #157347;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }

        .clinic-box {
            background-color: #f0fff4;
            padding: 15px;
            border-radius: 12px;
            border: 1px dashed #198754;
        }

        .links-container {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .footer-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #198754;
        }

        .booking-btn {
            background-color: #e8f5e9;
            color: #198754;
            border-radius: 8px;
            padding: 5px 15px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <h2><i class="fa-solid fa-user-md me-2"></i>Medical Login</h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Field --}}
                        <div class="mb-4">
                            <label for="email" class="form-label">E-mail Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                            </div>
                            @error('email')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Password Field --}}
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Clinic Selection Box --}}
                        <div class="mb-4 clinic-box">
                            <label for="clinic_id" class="form-label text-success"><i
                                    class="fa-solid fa-hospital-user me-1"></i> اختر العيادة الحالية</label>
                            <select name="clinic_id" id="clinic_id"
                                class="form-select @error('clinic_id') is-invalid @enderror"
                                style="border-left: 1px solid #ced4da;" required>
                                <option value="" selected disabled>-- اضغط لاختيار العيادة --</option>
                                @foreach (\App\Models\Clinic::all() as $clinic)
                                    <option value="{{ $clinic->id }}">🏥 {{ $clinic->name }}</option>
                                @endforeach
                            </select>
                            @error('clinic_id')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="footer-link small" href="{{ route('password.request') }}">Forget Password?</a>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> دخول النظام
                            </button>
                        </div>

                        {{-- Footer Links --}}
                        <div class="links-container">
                            <a href="{{ route('register') }}" class="footer-link">
                                <i class="fa-solid fa-user-plus me-1"></i> إنشاء حساب
                            </a>
                            <a href="{{ route('book') }}" class="footer-link booking-btn">
                                <i class="fa-solid fa-calendar-check me-1"></i> حجز موعد جديد
                            </a>
                        </div>
                    </form>
                </div>
                <p class="text-center mt-4 text-muted small">Medical Panel &copy; {{ date('Y') }} - All Rights Reserved
                </p>
            </div>
        </div>
    </div>
@endsection
