@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8fafc;
        }

        .premium-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #edf2f7;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            margin-bottom: 25px;
        }

        .filter-input {
            border-radius: 12px !important;
            border: 1px solid #eee !important;
            height: 45px !important;
            font-size: 13px !important;
        }

        .btn-filter {
            background: #198754;
            color: white;
            border-radius: 12px;
            height: 45px;
            font-weight: 800;
            border: none;
            transition: 0.3s;
            width: 100%;
        }

        .btn-filter:hover {
            background: #146c43;
            transform: translateY(-2px);
        }

        /* تنسيق Select2 الراقي */
        .select2-container--default .select2-selection--single {
            border: 1px solid #eee !important;
            border-radius: 12px !important;
            height: 45px !important;
            padding-top: 8px !important;
            background: #fdfdfd !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px !important;
            left: 10px !important;
            right: auto !important;
        }

        .chart-container {
            width: 200px;
            height: 200px;
            position: relative;
        }

        .chart-info {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            margin-bottom: 8px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-left: 10px;
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

    <div class="container-fluid p-4" dir="rtl">

        <div class="premium-card">
            <form action="{{ route('finance.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold text-muted mb-2 d-block">العيادة</label>
                    <select name="clinic_id" class="select2-now">
                        <option value="">كل العيادات</option>
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2 d-block">من تاريخ</label>
                    <input type="date" name="from_date" class="form-control filter-input"
                        value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2 d-block">إلى تاريخ</label>
                    <input type="date" name="to_date" class="form-control filter-input" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-filter">تطبيق الفلتر</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="premium-card h-100">
                    <h6 class="fw-bold mb-4">تحليل الحصص المالية</h6>
                    @if (collect($amounts)->sum() > 0)
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
                            <div class="chart-container">
                                <canvas id="finalChart"></canvas>
                                <div class="chart-info">
                                    <span class="text-muted small">الإجمالي</span><br>
                                    <span class="fw-bold text-success">{{ number_format(collect($amounts)->sum()) }}</span>
                                </div>
                            </div>
                            <div id="finalLegend" style="flex-grow: 1; width: 100%;">
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">لا توجد بيانات للفترة المحددة</div>
                    @endif
                </div>
            </div>

            <div class="col-lg-7">
                <div class="premium-card h-100">
                    <h6 class="fw-bold mb-4">سجل الإيرادات المفلتر</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-muted small">
                                    <th>المريض</th>
                                    <th>العيادة</th>
                                    <th>المبلغ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td class="fw-bold">{{ $invoice->patient->name }}</td>
                                        <td><span
                                                class="badge bg-light text-success px-3 py-2">{{ $invoice->clinic->name }}</span>
                                        </td>
                                        <td class="fw-bold text-success">{{ number_format($invoice->amount) }} ج.م</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // تشغيل Select2
            $('.select2-now').select2({
                dir: "rtl"
            });

            // تشغيل الرسم البياني
            const ctx = document.getElementById('finalChart');
            if (ctx) {
                const labels = {!! json_encode($labels) !!} || [];
                const amounts = ({!! json_encode($amounts) !!} || []).map(n => parseFloat(n) || 0);
                const total = amounts.reduce((a, b) => a + b, 0);
                const colors = ['#198754', '#2ebf76', '#52b788', '#95d5b2', '#d8f3dc'];

                new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: amounts,
                            backgroundColor: colors,
                            borderWidth: 4,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        cutout: '80%',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // بناء القائمة الجانبية
                const legendBox = document.getElementById('finalLegend');
                labels.forEach((label, i) => {
                    if (amounts[i] > 0) {
                        const perc = ((amounts[i] / total) * 100).toFixed(0);
                        legendBox.innerHTML += `
                        <div class="legend-item">
                            <div class="d-flex align-items-center">
                                <span class="dot" style="background: ${colors[i % colors.length]}"></span>
                                <span class="small fw-bold">${label}</span>
                            </div>
                            <span class="badge bg-white text-dark border small">${perc}%</span>
                        </div>
                    `;
                    }
                });
            }
        });
    </script>
    <a href="{{ route('dashboard') }}" class="floating-btn" title="Back to Dashboard">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
@endsection
