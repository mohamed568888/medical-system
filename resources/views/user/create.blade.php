@extends('layouts.app')

@section('styles')
    <style>
        /* تنسيق الحاوية والكارد */
        .form-container {
            max-width: 650px;
            margin: 0 auto;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* تنسيق الحقول */
        .form-label {
            font-weight: 700;
            color: #495057;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            transition: 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        }

        /* زر الحفظ */
        .btn-submit {
            background-color: #198754;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #157347;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }

        /* الزر العائم */
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #198754;
            color: white !important;
            border-radius: 50%;
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            z-index: 1000;
            text-decoration: none;
        }

        .floating-btn:hover {
            transform: scale(1.1);
            background-color: #157347;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="form-container">
            <div class="card">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold text-center">
                        <i class="fa-solid fa-user-plus me-2"></i> Register New User
                    </h5>
                </div>

                <div class="card-body p-4">
                    {{-- عرض أخطاء التحقق --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.store') }}" method="POST" enctype='multipart/form-data'>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter full name" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i
                                        class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="doctor@example.com" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Account Role</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i
                                        class="fa-solid fa-briefcase text-muted"></i></span>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="" selected disabled>Select a role...</option>
                                    <option value="doctor">Doctor (Full Access)</option>
                                    <option value="nurse">Nurse (Staff Access)</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-submit shadow-sm">
                            <i class="fa-solid fa-check-double me-1"></i> Create User Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- زرار الرجوع العائم لصفحة قائمة المستخدمين --}}
    <a href="{{ route('user.users') }}" class="floating-btn" title="Back to User Records">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
@endsection
