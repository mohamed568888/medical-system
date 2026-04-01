@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6;
        }

        .profile-card {
            border: none;
            border-radius: 25px;
            background: #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-top: 30px;
        }

        .profile-header {
            background: linear-gradient(45deg, #198754, #20c997);
            padding: 40px 30px;
            color: white;
            position: relative;
        }

        .profile-img-circle {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            backdrop-filter: blur(5px);
        }

        .info-section {
            padding: 30px;
        }

        .data-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #b2bec3;
            font-weight: 700;
            display: block;
            margin-bottom: 4px;
        }

        .data-value {
            font-weight: 600;
            color: #2d3436;
            font-size: 1.05rem;
            display: block;
            margin-bottom: 20px;
        }

        .medical-card {
            border-radius: 18px;
            padding: 20px;
            height: 100%;
            border: none;
            transition: 0.3s;
        }

        .diagnosis-box {
            background: #eafaf1;
            border-right: 5px solid #198754;
        }

        .chronic-box {
            background: #fff5f5;
            border-right: 5px solid #dc3545;
        }

        /* تصميم قسم الروشتات الجديد */
        .prescription-form-card {
            background: #f8f9fa;
            border-radius: 20px;
            border: 1px dashed #20c997;
            padding: 20px;
            margin-top: 25px;
        }

        .prescription-item {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }

        .prescription-item:hover {
            border-color: #20c997;
            transform: scale(1.01);
        }

        .attachment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .doc-item {
            background: #f8f9fa;
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            transition: 0.3s;
        }

        .doc-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: #198754;
        }

        .preview-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .btn-edit-profile {
            background: #ffc107;
            color: #000;
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 700;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white !important;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                style="border-radius: 15px; border: none; background: #d1e7dd; color: #0f5132;">
                <i class="fa-solid fa-circle-check me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container pb-5 text-end" dir="rtl">
        <div class="profile-card">
            {{-- الرأس --}}
            <div class="profile-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="profile-img-circle ms-3">
                        <i class="fa-solid fa-user-injured"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-1">{{ $patient->name }}</h2>
                        <span class="opacity-75"><i class="fa-solid fa-fingerprint ms-1"></i> ملف مريض رقم
                            #{{ $patient->id }}</span>
                    </div>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('patient.patients') }}" class="btn btn-back">
                        <i class="fa-solid fa-arrow-right ms-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>

            <div class="info-section">
                <div class="row">
                    {{-- العمود الأيمن: البيانات الشخصية والروشتات --}}
                    <div class="col-lg-4 border-start">
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-4">
                                <i class="fa-solid fa-id-card text-success ms-2"></i>البيانات الأساسية
                            </h5>
                            <span class="data-label">رقم الهاتف</span>
                            <span class="data-value text-primary"><i
                                    class="fa-solid fa-phone-flip ms-2"></i>{{ $patient->phone }}</span>

                            <span class="data-label">العيادة التابع لها</span>
                            <span class="data-value"><i
                                    class="fa-solid fa-hospital ms-2"></i>{{ $patient->clinic->name ?? 'غير محدد' }}</span>
                        </div>

                        {{-- قسم إضافة روشتة جديدة --}}
                        <div class="prescription-form-card shadow-sm">
                            <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-prescription ms-2"></i>إضافة روشتة
                                جديدة</h6>
                            <form action="{{ route('prescriptions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <div class="mb-2">
                                    <textarea name="medicine" class="form-control" rows="4" placeholder="اكتب الأدوية هنا... (R/)" required
                                        style="border-radius: 10px; font-size: 0.9rem;"></textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="notes" class="form-control form-control-sm"
                                        placeholder="ملاحظات إضافية..." style="border-radius: 8px;">
                                </div>
                                <button type="submit" class="btn btn-success w-100 fw-bold" style="border-radius: 10px;">
                                    <i class="fa-solid fa-plus-circle ms-1"></i> حفظ الروشتة
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- العمود الأيسر: الحالة الطبية والمرفقات وسجل الروشتات --}}
                    <div class="col-lg-8 pr-lg-4">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4">
                            <i class="fa-solid fa-notes-medical text-success ms-2"></i>الحالة الطبية والسجلات
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="medical-card diagnosis-box">
                                    <h6 class="fw-bold text-success"><i class="fa-solid fa-stethoscope ms-2"></i>التشخيص
                                        الطبي</h6>
                                    <p class="text-dark-50 small mb-0">{{ $patient->Diagnosis }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="medical-card chronic-box">
                                    <h6 class="fw-bold text-danger"><i
                                            class="fa-solid fa-triangle-exclamation ms-2"></i>الأمراض المزمنة</h6>
                                    <p class="text-dark-50 small mb-0">
                                        {{ $patient->Chronic_diseases ?? 'لا يوجد أمراض مسجلة' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- سجل الروشتات السابقة --}}
                        <div class="mt-4">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-clock-rotate-left ms-2"></i>سجل
                                الروشتات السابقة</h6>
                            @if ($patient->prescriptions->count() > 0)
                                {{-- التعديل تم هنا باستخدام sortByDesc --}}
                                @foreach ($patient->prescriptions->sortByDesc('created_at') as $presc)
                                    <div class="prescription-item shadow-sm">
                                        <div>
                                            <span class="badge bg-light text-dark border ms-2">{{ $presc->date }}</span>
                                            <strong class="small text-truncate d-inline-block"
                                                style="max-width: 200px;">{{ Str::limit($presc->medicine, 40) }}</strong>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('prescriptions.print', $presc->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fa-solid fa-print"></i> طباعة
                                            </a>
                                            <form action="{{ route('prescriptions.destroy', $presc->id) }}" method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-2">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small text-center bg-light py-3 rounded-3">لا توجد روشتات مسجلة مسبقاً
                                </p>
                            @endif
                        </div>

                        {{-- المرفقات المتعددة --}}
                        <div class="mt-5">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-paperclip ms-2"></i>الأشعة
                                والتحاليل المرفقة</h6>
                            @php $docs = (array) $patient->document; @endphp

                            @if (count($docs) > 0)
                                <div class="attachment-grid">
                                    @foreach ($docs as $index => $doc)
                                        <div class="doc-item">
                                            @php
                                                $extension = pathinfo($doc, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), [
                                                    'jpg',
                                                    'jpeg',
                                                    'png',
                                                    'webp',
                                                ]);
                                            @endphp

                                            @if ($isImage)
                                                <a href="{{ asset('storage/' . $doc) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $doc) }}" class="preview-img"
                                                        alt="Medical Document">
                                                </a>
                                            @else
                                                <div class="py-3">
                                                    <i class="fa-solid fa-file-pdf text-danger fa-3x mb-2"></i>
                                                    <p class="small text-truncate px-2">{{ basename($doc) }}</p>
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-center gap-1 mt-2">
                                                <a href="{{ asset('storage/' . $doc) }}" target="_blank"
                                                    class="btn btn-sm btn-success rounded-pill px-3"><i
                                                        class="fa-solid fa-eye"></i></a>
                                                <a href="{{ asset('storage/' . $doc) }}" download
                                                    class="btn btn-sm btn-outline-success rounded-pill px-3"><i
                                                        class="fa-solid fa-download"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 border rounded-4 bg-light">
                                    <p class="text-muted small mb-0">لا توجد ملفات مرفقة لهذا المريض</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- التذييل --}}
            <div class="card-footer bg-light border-0 p-4 d-flex justify-content-end gap-2">
                <a href="{{ route('patient.edit', $patient->id) }}" class="btn btn-edit-profile shadow-sm">
                    <i class="fa-solid fa-user-pen ms-2"></i> تعديل بيانات المريض
                </a>
            </div>
        </div>
    </div>
@endsection
