@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6;
        }

        /* تصميم كرت العيادة */
        .clinic-card {
            border: none;
            border-radius: 20px;
            background: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .clinic-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        /* الجزء العلوي الملون */
        .card-accent {
            height: 6px;
            width: 100%;
            background: linear-gradient(90deg, #198754, #20c997);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background: #e8f5e9;
            color: #198754;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .clinic-title {
            color: #2d3436;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .location-text {
            color: #636e72;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        /* إحصائيات العيادة */
        .stats-badge {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
        }

        .stats-number {
            display: block;
            font-weight: 700;
            color: #198754;
            font-size: 1.1rem;
        }

        .stats-label {
            font-size: 0.75rem;
            color: #b2bec3;
            text-transform: uppercase;
        }

        /* الأزرار */
        .btn-view {
            border-radius: 12px;
            padding: 10px;
            font-weight: 600;
            background: #198754;
            border: none;
            transition: 0.3s;
        }

        .btn-view:hover {
            background: #157347;
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }

        .action-link {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
            text-decoration: none;
        }

        .edit-link {
            background: #fff3cd;
            color: #856404;
        }

        .delete-btn {
            background: #f8d7da;
            color: #721c24;
            border: none;
        }

        /* الزر العائم */
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(45deg, #198754, #20c997);
            color: white !important;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 20px rgba(25, 135, 84, 0.4);
            z-index: 1000;
            transition: 0.3s;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        {{-- الهيدر --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">عياداتي المتاحة</h2>
                <p class="text-muted">إدارة العيادات وفروع العمل الخاصة بك</p>
            </div>
            <a href="{{ route('clinic.create') }}" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                <i class="fa-solid fa-plus-circle me-2"></i> إضافة عيادة جديدة
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            @forelse ($clinics as $clinic)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="clinic-card shadow-sm h-100">
                        <div class="card-accent"></div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="icon-box">
                                    <i class="fa-solid fa-hospital-user"></i>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('clinic.edit', $clinic->id) }}" class="action-link edit-link"
                                        title="تعديل">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('clinic.destroy', $clinic->id) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من الحذف؟ سيتم حذف جميع بيانات المرضى التابعين لهذه العيادة!')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-link delete-btn" title="حذف">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <h5 class="clinic-title">{{ $clinic->name }}</h5>
                            <div class="location-text mb-4">
                                <i class="fa-solid fa-location-dot text-danger me-2"></i>
                                {{ $clinic->address ?? 'العنوان غير محدد' }}
                            </div>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="stats-badge">
                                        <span class="stats-number">{{ $clinic->patients_count ?? 0 }}</span>
                                        <span class="stats-label">مريض</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stats-badge">
                                        <span class="stats-number">نشط</span>
                                        <span class="stats-label">الحالة</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('patient.patients', ['clinic' => $clinic->id]) }}"
                                class="btn btn-success btn-view w-100">
                                <i class="fa-solid fa-users me-2"></i> عرض سجل المرضى
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/1000/1000344.png" width="100"
                        class="mb-4 opacity-25">
                    <h4 class="text-muted">لا توجد عيادات مسجلة بعد</h4>
                    <a href="{{ route('clinic.create') }}" class="btn btn-outline-success mt-3 rounded-pill">ابدأ بإضافة أول
                        عيادة</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- الزر العائم --}}
    <a href="{{ route('dashboard') }}" class="floating-btn" title="العودة للوحة التحكم">
        <i class="fa-solid fa-house"></i>
    </a>
@endsection
