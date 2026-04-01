@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6;
        }

        .form-container {
            max-width: 900px;
            margin: 40px auto;
        }

        /* الكارت الرئيسي */
        .register-card {
            border: none;
            border-radius: 25px;
            background: #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(45deg, #198754, #20c997);
            padding: 30px;
            color: white;
        }

        /* العناوين الجانبية (Sections) */
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #198754;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f1f1;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            background: #eafaf1;
            padding: 8px;
            border-radius: 8px;
        }

        .form-label {
            font-weight: 700;
            color: #2d3436;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        /* تنسيق الحقول */
        .input-group-text {
            background-color: #f8f9fa;
            border-left: none;
            color: #198754;
            border-radius: 12px 0 0 12px !important;
        }

        .form-control,
        .form-select {
            border-radius: 0 12px 12px 0 !important;
            padding: 12px;
            border-right: none;
            border-color: #dee2e6;
            transition: 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #198754;
            background-color: #fcfdfc;
        }

        /* زر الحفظ */
        .btn-submit-register {
            background: linear-gradient(45deg, #198754, #20c997);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: 0.3s;
            color: white;
        }

        .btn-submit-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
            color: white;
        }

        /* المرفقات */
        .upload-box {
            background: #f8f9fa;
            border: 2px dashed #d1e7dd;
            border-radius: 15px;
            padding: 20px;
            transition: 0.3s;
        }

        .upload-box:hover {
            border-color: #198754;
            background: #f1f8f5;
        }

        /* الزر العائم */
        .floating-back {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 55px;
            height: 55px;
            background: #6c757d;
            color: white !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: 0.3s;
            text-decoration: none;
        }

        .floating-back:hover {
            transform: scale(1.1);
            background: #495057;
        }
    </style>
@endsection

@section('content')
    <div class="container form-container">
        <div class="register-card shadow-lg">
            {{-- الهيدر --}}
            <div class="register-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><i class="fa-solid fa-user-plus me-2"></i>تسجيل مريض جديد</h3>
                    <small class="opacity-75">قم بإدخال البيانات الأساسية والطبية بدقة</small>
                </div>
                <i class="fa-solid fa-notes-medical fa-3x opacity-25"></i>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('patient.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- القسم الأول: البيانات الشخصية --}}
                    <div class="section-title">
                        <i class="fa-solid fa-id-card"></i>البيانات الشخصية
                    </div>

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label">الاسم الكامل</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="name" class="form-control"
                                    placeholder="أدخل اسم المريض رباعي" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">العيادة المخصصة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-hospital"></i></span>
                                <select name="clinic_id" class="form-select" required>
                                    <option value="" selected disabled>اختر العيادة...</option>
                                    @foreach ($clinics as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">رقم الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="01xxxxxxxxx"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">تاريخ الميلاد</label>
                            <input type="date" name="birth_date" class="form-control"
                                style="border-radius: 12px !important; border-right: 1px solid #dee2e6;" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">النوع</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-venus-mars"></i></span>
                                <select name="gender" class="form-select" required>
                                    <option value="" selected disabled>اختر النوع .....</option>
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">العنوان الحالي</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-map-location-dot"></i></span>
                                <input type="text" name="address" class="form-control"
                                    placeholder="المحافظة، المدينة، اسم الشارع" required>
                            </div>
                        </div>
                    </div>

                    {{-- القسم الثاني: السجل الطبي --}}
                    <div class="section-title">
                        <i class="fa-solid fa-file-waveform"></i>التاريخ الطبي والمرفقات
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label text-danger">الأمراض المزمنة</label>
                            <textarea name="Chronic_diseases" class="form-control" rows="2"
                                style="border-radius: 12px !important; border-right: 1px solid #dee2e6;"
                                placeholder="اذكر السكر، الضغط، أو أي حساسيات دوائية إن وجدت"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">إرفاق مستندات رقمية</label>
                            <div class="upload-box">
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                                    <input type="file" name="document" class="form-control">
                                </div>
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info me-1"></i> يمكنك رفع نتائج التحاليل، الأشعة، أو
                                    التقارير السابقة (PDF, JPG, PNG).
                                </small>
                            </div>
                        </div>
                        {{-- القسم الثالث: البيانات المالية (دفع الكشف) --}}
                        <div class="section-title">
                            <i class="fa-solid fa-cash-register"></i>بيانات الدفع 
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">مبلغ الكشف  </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-money-bill-1-wave"></i></span>
                                    <input type="number" name="payment_amount" class="form-control" placeholder="0.00">
                                </div>
                                <small class="text-muted">اترك الحقل فارغاً إذا لم يتم التحصيل الآن</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">نوع الخدمة</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-hand-holding-medical"></i></span>
                                    <select name="payment_type" class="form-select">
                                        <option value="كشف" selected>كشف</option>
                                        <option value="استشارة">استشارة</option>
                                        <option value="عملية">عملية</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- زر الحفظ النهائي --}}
                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-submit-register w-100 shadow">
                                <i class="fa-solid fa-circle-check me-2"></i>إتمام عملية التسجيل وحفظ البيانات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- الزر العائم للرجوع --}}
    <a href="{{ route('patient.patients') }}" class="floating-back" title="إلغاء والعودة">
        <i class="fa-solid fa-chevron-left"></i>
    </a>
@endsection
