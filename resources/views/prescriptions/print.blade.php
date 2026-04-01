<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>روشتة - {{ $prescription->patient->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* إعدادات الخط واللغة */
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        /* إعدادات الصفحة A4 */
        @page {
            size: A4;
            margin: 0;
            /* نلغي هوامش المتصفح الافتراضية */
        }

        .prescription-wrapper {
            width: 210mm;
            /* عرض ورقة A4 */
            min-height: 297mm;
            /* طول ورقة A4 */
            padding: 20mm;
            /* هوامش داخلية للورقة */
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            /* لضمان أن الـ padding لا يزيد العرض */
            position: relative;
        }

        /* تنسيق المحتوى */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 4px solid #198754;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .doc-name {
            margin: 0;
            color: #198754;
            font-size: 32px;
            font-weight: 700;
        }

        .doc-specialty {
            margin: 5px 0 0 0;
            font-size: 18px;
            color: #444;
            font-weight: 600;
        }

        .clinic-info h1 {
            margin: 0;
            color: #198754;
            font-size: 28px;
        }

        .patient-info {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border: 1px solid #eee;
            font-size: 18px;
        }

        .prescription-body {
            min-height: 180mm;
            /* نترك مساحة كافية للأدوية قبل التذييل */
        }

        .clinic-logo-info {
            text-align: left;
            /* لجعل بيانات العيادة تظهر في اليسار والاسم في اليمين */
        }

        .clinic-name {
            margin: 0;
            font-size: 22px;
            color: #333;
        }

        .clinic-address {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #777;
            max-width: 250px;
        }

        .rx-symbol {
            font-size: 45px;
            font-weight: bold;
            color: #198754;
            margin-bottom: 15px;
        }

        .medicine-list {
            font-size: 22px;
            line-height: 2;
            white-space: pre-line;
            padding-right: 15px;
            color: #333;
        }

        .footer {
            position: absolute;
            bottom: 15mm;
            left: 0;
            right: 0;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
            font-size: 14px;
            color: #666;
        }

        /* زر الطباعة */
        .print-button {
            text-align: center;
            padding: 20px;
            background: #333;
        }

        .btn {
            background: #198754;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* إعدادات الطباعة الفعلية */
        @media print {
            body {
                background: none;
            }

            .print-button {
                display: none;
                /* إخفاء زر الطباعة عند المعاينة */
            }

            .prescription-wrapper {
                margin: 0;
                box-shadow: none;
                width: 210mm;
                height: 297mm;
            }

            .header {
                border-bottom-color: #198754 !important;
            }

            .doc-name {
                color: #198754 !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-button">
        <button onclick="window.print()" class="btn">اضغط هنا للطباعة</button>
    </div>

    <div class="prescription-wrapper">
        {{-- بيانات العيادة --}}
        <div class="header">
            <div class="doctor-details">
                {{-- اسم الدكتور - يفضل جلب الاسم من Auth::user()->name إذا كان هو الطبيب --}}
                <h1 class="doc-name">د/ {{ Auth::user()->name }}</h1>

                {{-- مكان كتابة التخصص --}}
                <p class="doc-specialty">أخصائي جراحة عامة وتجميل</p>
                {{-- ملاحظة: يمكنك جعل التخصص متغيراً من قاعدة البيانات أيضاً --}}
            </div>

            <div class="clinic-logo-info text-left">
                <h2 class="clinic-name">{{ $prescription->patient->clinic->name ?? 'عيادة النخبة' }}</h2>
                <p class="clinic-address">{{ $prescription->patient->clinic->address ?? 'العنوان بالتفصيل' }}</p>
            </div>
        </div>

        {{-- بيانات المريض --}}
        <div class="patient-info">
            <div>
                <strong>اسم المريض:</strong> {{ $prescription->patient->name }}
            </div>
            <div>
                <strong>التاريخ:</strong> {{ $prescription->created_at->format('Y/m/d') }}
            </div>
        </div>

        {{-- المحتوى الطبي --}}
        <div class="prescription-body">
            <div class="rx-symbol">R/</div>
            <div class="medicine-list">
                {{ $prescription->medicine }}
            </div>

            @if ($prescription->notes)
                <div style="margin-top: 30px; font-size: 16px; color: #555; border-top: 1px dashed #eee; pt-3">
                    <strong>ملاحظات:</strong><br>
                    {{ $prescription->notes }}
                </div>
            @endif
        </div>

        {{-- التذييل --}}
        <div class="footer">
           by:Eng / Mohamed Anas  هذه الروشتة تم إنشاؤها عبر نظام إدارة العيادة الذكي
        </div>
    </div>

</body>

</html>
