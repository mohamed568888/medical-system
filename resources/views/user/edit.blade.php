@extends('layouts.app')

@section('styles')
    <style>
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

        /* زر التحديث */
        .btn-update {
            background-color: #198754;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: 0.3s;
        }

        .btn-update:hover {
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
                        <i class="fa-solid fa-user-pen me-2"></i> Edit Information
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.update') }}" method="POST" enctype='multipart/form-data'>
                        @csrf
                        {{-- الحقل المخفي للـ ID القديم --}}
                        <input type="hidden" name="old_id" value="{{ $user->id }}">

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $user->name }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i
                                        class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Leave it empty if you do not want to change.">
                            </div>
                            <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Only fill this if you
                                want to reset the user's password.</small>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Role</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i
                                        class="fa-solid fa-briefcase text-muted"></i></span>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="nurse" {{ $user->role == 'nurse' ? 'selected' : '' }}>Nurse</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-update shadow-sm">
                            <i class="fa-solid fa-rotate me-1"></i> Update User Records
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- زرار الرجوع العائم --}}
    <a href="{{ route('user.users') }}" class="floating-btn" title="Back to User Records">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
@endsection
