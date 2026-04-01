@extends('layouts.app')

@section('styles')
    <style>
        .reservation-card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            background-color: white;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8f9fa !important;
            color: #198754 !important;
            text-transform: uppercase;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e9ecef !important;
            padding: 15px;
        }

        .table tbody td {
            padding: 14px;
            vertical-align: middle;
            color: #495057;
            font-size: 14px;
        }

        tr:hover td {
            background-color: #f1fcf6;
            transition: 0.3s ease;
        }

        .id-badge {
            background-color: #e9ecef;
            color: #6c757d;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
        }

        .clinic-badge {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 5px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
        }

        .date-text {
            color: #198754;
            font-weight: 500;
        }

        /* تنسيق زر الحذف */
        .btn-delete {
            color: #dc3545;
            background-color: #fff5f5;
            border: 1px solid #ffc9c9;
            border-radius: 8px;
            padding: 5px 10px;
            transition: 0.3s;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: white;
            transform: scale(1.05);
        }

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
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-radius: 15px; background-color: #e8f5e9; color: #2e7d32;">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-check-circle me-2 fs-5"></i>
                    <div>
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="reservation-card card p-0">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h4 class="text-success mb-0 fw-bold">
                    <i class="fa-solid fa-calendar-check me-2"></i> Booked Patients List
                </h4>
                <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">
                    Total: {{ $patients->count() }}
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">#</th>
                                <th width="20%">Patient Name</th>
                                <th width="15%">Clinic</th>
                                <th width="20%">Phone Number</th>
                                <th width="20%">Registration Date</th>
                                <th width="20%">Actions</th> {{-- العمود الجديد --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patients as $item)
                                <tr class="text-center">
                                    <td><span class="id-badge">{{ $item->id }}</span></td>

                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-2 text-success d-none d-md-flex"
                                                style="width: 32px; height: 32px; align-items: center; justify-content: center;">
                                                <i class="fa-solid fa-user" style="font-size: 11px;"></i>
                                            </div>
                                            <span class="fw-bold">{{ $item->name }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="clinic-badge">
                                            <i class="fa-solid fa-house-medical me-1"></i>
                                            {{ $item->clinic->name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>
                                        <a href="tel:{{ $item->phone }}" class="text-decoration-none text-muted">
                                            <i class="fa-solid fa-phone-flip me-1 small"></i> {{ $item->phone }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="date-text">
                                            <i class="fa-regular fa-calendar-days me-1 small"></i>
                                            {{ $item->created_at->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- زر الحذف مع التأكيد --}}
                                        <form action="{{ route('booking.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete btn btn-sm">
                                                <i class="fa-solid fa-trash-can me-1"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-calendar-xmark fa-3x mb-3 d-block text-light"></i>
                                            <h5>No Bookings Yet</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="floating-btn" title="Back to Dashboard">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
@endsection
