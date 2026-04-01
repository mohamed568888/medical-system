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

        .edit-card {
            border: none;
            border-radius: 25px;
            background: #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .edit-header {
            background: linear-gradient(45deg, #198754, #20c997);
            padding: 30px;
            color: white;
        }

        .form-label {
            font-weight: 700;
            color: #2d3436;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-left: 8px;
            color: #198754;
        }

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

        .form-control:focus {
            box-shadow: none;
            border-color: #198754;
            background-color: #fcfdfc;
        }

        .file-management-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
        }

        /* تنسيق برواز الملف مع زر الحذف */
        .file-wrapper {
            position: relative;
            display: inline-block;
        }

        .current-file-badge {
            display: inline-flex;
            align-items: center;
            background: #e6fffa;
            color: #047857;
            padding: 8px 15px;
            border-radius: 10px;
            border: 1px solid #b2f5ea;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .btn-delete-file {
            position: absolute;
            top: -8px;
            left: -8px;
            background: #dc3545;
            color: white !important;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 100;
            /* رفع الطبقة لضمان الاستجابة للضغط */
        }

        .btn-delete-file:hover {
            background: #a71d2a;
            transform: scale(1.1);
        }

        .btn-submit-update {
            background: linear-gradient(45deg, #198754, #20c997);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .btn-submit-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
        }

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
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                style="border-radius: 15px; border: none; background: #d1e7dd; color: #0f5132;">
                <i class="fa-solid fa-circle-check me-2"></i>
                <div>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container form-container">
        <div class="edit-card shadow-lg">
            {{-- الهيدر --}}
            <div class="edit-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><i class="fa-solid fa-user-gear me-2"></i>تعديل بيانات المريض</h3>
                    <small class="opacity-75">أنت الآن تقوم بتحديث ملف: {{ $patient->name }}</small>
                </div>
                <i class="fa-solid fa-file-medical-alt fa-3x opacity-25"></i>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('patient.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_id" value="{{ $patient->id }}">

                    <div class="row g-4">
                        {{-- الاسم --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-user"></i>الاسم الكامل</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-signature"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ $patient->name }}"
                                    required>
                            </div>
                        </div>

                        {{-- العيادة --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-hospital"></i>العيادة المخصصة</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-house-medical"></i></span>
                                <select name="clinic_id" class="form-select" required>
                                    @foreach ($clinics as $clinic)
                                        <option value="{{ $clinic->id }}"
                                            {{ $patient->clinic_id == $clinic->id ? 'selected' : '' }}>
                                            {{ $clinic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- الهاتف --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-phone"></i>رقم الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-mobile-screen"></i></span>
                                <input type="text" name="phone" class="form-control" value="{{ $patient->phone }}"
                                    required>
                            </div>
                        </div>

                        {{-- تاريخ الميلاد --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-calendar"></i>تاريخ الميلاد</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-calendar-check"></i></span>
                                <input type="date" name="birth_date" class="form-control"
                                    value="{{ $patient->birth_date }}" required>
                            </div>
                        </div>

                        {{-- التشخيص --}}
                        <div class="col-12">
                            <label class="form-label"><i class="fa-solid fa-stethoscope"></i>التشخيص الطبي و اي ملاحظات</label>
                            <textarea name="Diagnosis" class="form-control" rows="3"
                                style="border-radius: 12px !important; border-right: 1px solid #dee2e6;" required>{{ $patient->Diagnosis }}</textarea>
                        </div>

                        {{-- الأمراض المزمنة --}}
                        <div class="col-12">
                            <label class="form-label text-danger"><i class="fa-solid fa-circle-exclamation me-1"></i>الأمراض
                                المزمنة (إن وجدت)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-virus-covid"></i></span>
                                <input type="text" name="Chronic_diseases" class="form-control"
                                    value="{{ $patient->Chronic_diseases }}" placeholder="مثال: ضغط، سكر...">
                            </div>
                        </div>

                        {{-- إدارة الملفات --}}
                        <div class="col-12">
                            <div class="file-management-box">
                                <label class="form-label mb-3"><i class="fa-solid fa-paperclip"></i>إدارة المرفقات (أشعة /
                                    تحاليل)</label>

                                @php
                                    $docs = $patient->document ?: [];
                                @endphp

                                @if (count($docs) > 0)
                                    <div class="d-flex flex-wrap gap-3 mb-4">
                                        @foreach ($docs as $index => $doc)
                                            <div class="file-wrapper">
                                                {{-- زر الحذف الفردي --}}
                                                <button type="button" class="btn-delete-file"
                                                    onclick="confirmDeleteFile('{{ $doc }}')" title="حذف الملف">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>

                                                <div class="current-file-badge shadow-sm">
                                                    <i class="fa-solid fa-file-medical text-success me-2"></i>
                                                    <a href="{{ asset('storage/' . $doc) }}" target="_blank"
                                                        class="text-decoration-none text-dark small me-2">
                                                        مرفق {{ $index + 1 }}
                                                    </a>
                                                    <a href="{{ asset('storage/' . $doc) }}" download>
                                                        <i class="fa-solid fa-circle-arrow-down text-primary small"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-light border-0 py-2 small text-muted mb-3">
                                        <i class="fa-solid fa-info-circle me-1"></i> لا توجد مرفقات حالية.
                                    </div>
                                @endif

                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-upload"></i></span>
                                    <input type="file" name="documents[]" class="form-control" multiple>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fa-solid fa-info-circle me-1"></i> رفع ملفات جديدة سيتم إضافتها للسجل الحالي.
                                </small>
                            </div>
                        </div>

                        {{-- زر الحفظ --}}
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success btn-submit-update w-100 shadow">
                                <i class="fa-solid fa-floppy-disk me-2"></i>حفظ كافة التعديلات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- فورم مخفي لمعالجة حذف الملف --}}
    <form id="delete-file-form" action="{{ route('patient.deleteFile') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        <input type="hidden" name="file_path" id="delete-file-path-input">
    </form>

    {{-- الزر العائم --}}
    <a href="{{ route('patient.patients') }}" class="floating-back" title="رجوع">
        <i class="fa-solid fa-chevron-left"></i>
    </a>

    {{-- كود السكريبت هنا مباشرة لضمان العمل --}}
    <script>
        function confirmDeleteFile(filePath) {
            console.log("Button clicked for: " + filePath);
            if (confirm('هل أنت متأكد من حذف هذا الملف نهائيًا؟')) {
                var input = document.getElementById('delete-file-path-input');
                var form = document.getElementById('delete-file-form');
                if (input && form) {
                    input.value = filePath;
                    form.submit();
                } else {
                    alert("خطأ: لم يتم العثور على عناصر الفورم المخفي.");
                }
            }
        }
    </script>
@endsection
