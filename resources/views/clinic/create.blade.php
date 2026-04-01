@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f6; }

        /* تنسيق الكارد المركزي */
        .form-container {
            max-width: 550px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            border-top: 5px solid #198754;
        }

        .form-label {
            color: #2d3436;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        /* تحسين شكل حقول الإدخال */
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #198754;
            border-radius: 12px 0 0 12px !important;
        }

        .form-control {
            border-radius: 0 12px 12px 0 !important;
            padding: 12px;
            border-left: none;
            border-color: #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #198754;
            background-color: #fcfdfc;
        }

        /* زر الحفظ */
        .btn-save {
            background: linear-gradient(45deg, #198754, #20c997);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(25, 135, 84, 0.2);
            background: linear-gradient(45deg, #157347, #1ba37a);
        }

        /* الزر العائم */
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #6c757d;
            color: white !important;
            border-radius: 50%;
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
        }

        .floating-btn:hover {
            background-color: #495057;
            transform: scale(1.1);
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header-icon {
            width: 60px;
            height: 60px;
            background: #e8f5e9;
            color: #198754;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            margin: 0 auto 15px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="form-container">
            <div class="page-header">
                <div class="header-icon shadow-sm">
                    <i class="fa-solid fa-hospital-plus"></i>
                </div>
                <h3 class="fw-bold text-dark">إضافة عيادة جديدة</h3>
                <p class="text-muted small">قم بإدخال بيانات الفرع الجديد لإدراجه في النظام</p>
            </div>

            <form action="{{ route('clinic.store') }}" method="POST">
                @csrf
                
                {{-- حقل اسم العيادة --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">اسم العيادة / الفرع</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                        <input type="text" name="name" class="form-control" 
                               placeholder="مثال: عيادة القاهرة - التجمع" required>
                    </div>
                </div>

                {{-- حقل العنوان --}}
                <div class="mb-5">
                    <label class="form-label fw-bold">عنوان العيادة</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-map-location-dot"></i></span>
                        <input type="text" name="address" class="form-control" 
                               placeholder="أدخل العنوان بالتفصيل">
                    </div>
                </div>

                {{-- زر الحفظ --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-save text-white">
                        <i class="fa-solid fa-check-double me-2"></i> حفظ بيانات العيادة
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- زرار الرجوع العائم --}}
    <a href="{{ route('clinic.index') }}" class="floating-btn" title="العودة لقائمة العيادات">
        <i class="fa-solid fa-chevron-left"></i>
    </a>
@endsection