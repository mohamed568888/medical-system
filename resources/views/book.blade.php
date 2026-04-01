@extends('layouts.app')

@section('title', 'Book an Appointment')

@section('styles')
    <style>
        .booking-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(135deg, #198754, #2ecc71);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ebedef;
            background-color: #f8f9fa;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #fff;
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        }

        .btn-confirm {
            background-color: #198754;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-confirm:hover {
            background-color: #157347;
            transform: translateY(-2px);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            background-color: #e9ecef;
            border: 1px solid #ebedef;
        }

        .form-control {
            border-radius: 10px;
        }

        /* تأثير نبض بسيط للأيقونة */
        .swal2-icon.swal2-success [class^=swal2-success-line] {
            background-color: #198754 !important;
        }

        .swal2-styled.swal2-confirm {
            border-radius: 10px !important;
            padding: 10px 30px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5 mb-5">
        <div class="booking-container">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0 fw-bold">
                        <i class="fa-solid fa-calendar-plus me-2"></i> Appointment Booking
                    </h4>
                    <p class="small mb-0 mt-2 opacity-75">Please fill in the patient information to secure a slot</p>
                </div>

                <div class="card-body p-4">


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fa-solid fa-triangle-exclamation me-2"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('book.submit') }}" enctype='multipart/form-data'>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user text-success"></i></span>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter patient's name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="clinic_id" class="form-label">Select Clinic</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-hospital text-success"></i></span>
                                    <select name="clinic_id" id="clinic_id" class="form-select" required>
                                        <option value="" selected disabled>Choose the appropriate clinic...</option>
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone text-success"></i></span>
                                <input type="text" name="phone" id="phone" class="form-control"
                                    placeholder="e.g. 01012345678" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-dot text-success"></i></span>
                                <input type="text" name="address" id="address" class="form-control"
                                    placeholder="Current city or area" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-confirm btn-success w-100 shadow-sm">
                            <i class="fa-solid fa-check-circle me-2"></i> Confirm Booking Request
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="text-decoration-none text-muted small">
                    <i class="fa-solid fa-arrow-left me-1"></i> Go back to previous page
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'تم الحجز بنجاح!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'موافق',
                    confirmButtonColor: '#198754'
                });
            });
        </script>
    @endif
@endsection
