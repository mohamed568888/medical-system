@extends('layouts.app')

@section('styles')
    <style>
        /* تنسيق كارت البروفايل */
        .profile-card {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            background: linear-gradient(135deg, #198754, #2ecc71);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 40px;
            color: #198754;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 25px;
            border-bottom: 1px solid #f1f1f1;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #6c757d;
            font-size: 15px;
        }

        .value {
            color: #212529;
            font-weight: 600;
        }

        /* تنسيق الزر العائم */
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
            transition: all 0.3s ease;
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
    <div class="container mt-5">
        <div class="profile-card">
            {{-- رأس البروفايل --}}
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <h3 class="mb-0">{{ $user->name }}</h3>
                <span class="badge bg-light text-success mt-2 px-3 rounded-pill">{{ strtoupper($user->role) }}</span>
            </div>

            {{-- تفاصيل المستخدم --}}
            <div class="profile-body p-3">
                <div class="detail-row">
                    <span class="label"><i class="fa-solid fa-id-card me-2"></i> User ID</span>
                    <span class="value">#{{ $user->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="label"><i class="fa-solid fa-envelope me-2"></i> Email Address</span>
                    <span class="value">{{ $user->email }}</span>
                </div>

                <div class="detail-row">
                    <span class="label"><i class="fa-solid fa-shield-halved me-2"></i> Account Role</span>
                    <span class="value text-capitalize">{{ $user->role }}</span>
                </div>

                <div class="detail-row">
                    <span class="label"><i class="fa-solid fa-lock me-2"></i> Password</span>
                    <span class="value text-muted">•••••••• (Secured)</span>
                </div>

                <div class="p-4 text-center">
                    <a href="{{ route('user.users') }}" class="btn btn-outline-success px-4 rounded-pill">
                        <i class="fa-solid fa-users me-2"></i> View All Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- زرار الرجوع العائم لصفحة المستخدمين --}}
    <a href="{{ route('user.users') }}" class="floating-btn" title="Back to Users List">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
@endsection
