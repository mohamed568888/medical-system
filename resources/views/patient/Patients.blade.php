@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6;
        }

        .main-container {
            padding: 30px;
        }

        .page-header {
            background: white;
            padding: 20px 30px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
            border: 1px solid #edf2f7;
        }

        .filter-label {
            font-weight: 700;
            color: #198754;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-custom {
            border: 1px solid #e2e8f0;
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 600;
        }

        .table-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .custom-table thead th {
            background: #f8f9fa;
            color: #198754;
            font-weight: 700;
            padding: 15px;
            text-align: center;
        }

        .custom-table td {
            background: white;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #f1f1f1;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
        }

        .clinic-badge {
            background: #eafaf1;
            color: #198754;
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.8rem;
            border: 1px solid #d1e7dd;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 3px;
            transition: 0.2s;
            text-decoration: none;
        }

        .btn-show {
            background: #e0f2fe;
            color: #0284c7;
        }

        .btn-show:hover {
            background: #0284c7;
            color: white;
        }

        .btn-edit {
            background: #fff4e5;
            color: #f39c12;
        }

        .btn-edit:hover {
            background: #f39c12;
            color: white;
        }

        .btn-delete {
            background: #fff5f5;
            color: #e74c3c;
            border: none;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }

        .btn-add-main {
            background: linear-gradient(45deg, #198754, #20c997);
            color: white !important;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
        }

        .doc-link {
            color: #198754;
            transition: 0.2s;
            margin: 0 2px;
        }

        .doc-link:hover {
            color: #146c43;
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
    </style>
@endsection

@section('content')
    <div class="container-fluid main-container text-end" dir="rtl">

        <div class="page-header shadow-sm">
            <div class="header-title">
                <h3 class="mb-1"><i class="fa-solid fa-users-viewfinder text-success ms-2"></i>سجل المرضى الموحد</h3>
                <p class="text-muted small mb-0">إدارة وعرض بيانات المرضى في كافة العيادات</p>
            </div>
            <a href="{{ route('patient.create') }}" class="btn-add-main">
                <i class="fa-solid fa-plus ms-1"></i> إضافة مريض جديد
            </a>
        </div>

        {{-- قسم البحث والفلترة --}}
        <div class="filter-section shadow-sm">
            <form action="{{ route('patient.patients') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="filter-label"><i class="fa-solid fa-filter ms-1"></i>تصفية حسب العيادة:</label>
                        <select name="clinic_id" class="form-select form-control-custom" onchange="this.form.submit()">
                            <option value="all" {{ request('clinic_id') == 'all' ? 'selected' : '' }}>🌍 عرض جميع
                                العيادات</option>
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}"
                                    {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                    🏥 {{ $clinic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="filter-label"><i class="fa-solid fa-magnifying-glass ms-1"></i>بحث:</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-custom"
                                placeholder="الاسم أو الهاتف..." value="{{ request('search') }}">
                            <button class="btn btn-success px-4" type="submit"
                                style="border-radius: 10px 0 0 10px;">بحث</button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('patient.patients') }}" class="btn btn-light w-100 fw-bold"
                            style="border-radius: 10px; border: 1px solid #ddd;">إلغاء الفلاتر</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-card shadow-sm">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>العيادة</th>
                            <th>رقم الهاتف</th>
                            <th>التشخيص</th>
                            <th>المرفقات</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $patient)
                            <tr>
                                <td class="text-muted">
                                    {{ ($patients->currentPage() - 1) * $patients->perPage() + $loop->iteration }}
                                </td>
                                <td class="fw-bold text-dark">{{ $patient->name }}</td>
                                <td>
                                    <span class="clinic-badge">
                                        {{ $patient->clinic->name ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="text-secondary">{{ $patient->phone }}</td>
                                <td class="text-truncate" style="max-width: 150px;">{{ $patient->Diagnosis }}</td>
                                <td>
                                    @php
                                        // تأكد من تحويل المرفقات لمصفوفة لتجنب خطأ Array to string conversion
                                        $docs = (array) $patient->document;
                                    @endphp

                                    @if (count($docs) > 0)
                                        <div class="d-flex justify-content-center gap-1">
                                            @foreach ($docs as $doc)
                                                <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="doc-link"
                                                    title="عرض مرفق">
                                                    <i class="fa-solid fa-file-medical fa-lg"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small">---</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{-- زر عرض التفاصيل --}}
                                        <a href="{{ route('patient.show', $patient->id) }}" class="btn-action btn-show"
                                            title="عرض التفاصيل">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        {{-- زر التعديل --}}
                                        <a href="{{ route('patient.edit', $patient->id) }}" class="btn-action btn-edit"
                                            title="تعديل">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </a>

                                        {{-- زر الحذف --}}
                                        <form action="{{ route('patient.destroy', $patient->id) }}" method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف المريض وجميع مرفقاته؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="حذف">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">لا توجد بيانات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $patients->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    {{-- زرار الرجوع العائم للداشبورد --}}
    <a href="{{ route('dashboard') }}" class="floating-btn" title="Back to Dashboard">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
@endsection
