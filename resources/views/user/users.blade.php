@extends('layouts.app')

@section('styles')
    <style>
        /* تحسين مظهر الجدول والكارد */
        .card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            /* مسافة بين الصفوف */
        }

        th {
            background-color: #f8f9fa !important;
            color: #198754 !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            border: none !important;
            padding: 15px !important;
        }

        td {
            background-color: white;
            padding: 15px !important;
            vertical-align: middle;
            border-top: 1px solid #eee !important;
            border-bottom: 1px solid #eee !important;
        }

        tr:hover td {
            background-color: #f1fcf6;
        }

        /* تنسيق الأزرار */
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 2px;
            transition: 0.3s;
        }

        /* تنسيق الزر العائم (توحيد اللون للأخضر) */
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #198754;
            color: white !important;
            border: none;
            border-radius: 50%;
            width: 55px;
            height: 55px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            z-index: 1000;
            text-decoration: none;
        }

        .floating-btn:hover {
            transform: scale(1.1);
            background-color: #157347;
        }

        .badge-role {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background-color: #e8f5e9;
            color: #1b5e20;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold text-success">
                    <i class="fa-solid fa-users-rectangle me-2"></i> User Records
                    <small class="text-muted fw-normal ms-1">({{ $users->count() }})</small>
                </h5>

                <a href="{{ route('user.create') }}" class="btn btn-success btn-sm px-3 rounded-pill">
                    <i class="fa-solid fa-user-plus me-1"></i> Add New User
                </a>
            </div>

            <div class="card-body table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th class="text-center">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $item)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $item->id }}</td>
                                <td class="fw-bold">{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <span class="badge-role">{{ strtoupper($item->role) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('user.show', $item->id) }}" class="btn btn-action btn-outline-success"
                                        title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('user.edit', $item->id) }}" class="btn btn-action btn-outline-primary"
                                        title="Edit">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <a href="{{ route('user.delete', $item->id) }}"
                                        class="btn btn-action btn-outline-danger" title="Delete"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- زرار الرجوع العائم للداشبورد --}}
    <a href="{{ route('dashboard') }}" class="floating-btn" title="Back to Dashboard">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
@endsection
