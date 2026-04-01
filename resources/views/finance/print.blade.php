<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>وصل استلام #{{ $invoice->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* إعدادات الصفحة للطباعة الحرارية */
        @page {
            size: 60mm auto;
            /* عرض ورق الريسيت الحراري */
            margin: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        .receipt-container {
            width: 80mm;
            background: #fff;
            margin: 20px auto;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* تنسيق الهيدر مثل المطاعم */
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            font-weight: 800;
        }

        .header p {
            margin: 0;
            font-size: 12px;
            color: #555;
        }

        /* تفاصيل العملية */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 13px;
        }

        .info-row .label {
            font-weight: bold;
        }

        /* منطقة المبلغ (الإجمالي) */
        .total-section {
            border-top: 1px double #000;
            border-bottom: 1px double #000;
            margin: 15px 0;
            padding: 10px 0;
            text-align: center;
        }

        .total-label {
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        .total-amount {
            font-size: 22px;
            font-weight: 900;
            color: #000;
        }

        /* الباركود أو الختم الوهمي */
        .barcode {
            text-align: center;
            margin: 15px 0;
            font-family: 'monospace';
            font-size: 12px;
            letter-spacing: 2px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 10px;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }

        /* أزرار التحكم - تختفي عند الطباعة */
        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
            font-size: 14px;
        }

        .btn-print {
            background: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
        }

        @media print {
            body {
                background: #fff;
            }

            .receipt-container {
                margin: 0;
                box-shadow: none;
                width: 100%;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt-container">
        <div class="header">
            <h2>{{ $invoice->patient->clinic->name ?? 'عيادة التخصصي' }}</h2>
            <p>دكتور: {{ Auth::user()->name }}</p>
            <p>تاريخ: {{ $invoice->created_at->format('Y/m/d H:i') }}</p>
        </div>

        <div class="details">
            <div class="info-row">
                <span class="label">رقم الإيصال:</span>
                <span>#{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="label">المريض:</span>
                <span>{{ $invoice->patient->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">نوع الخدمة:</span>
                <span>{{ $invoice->type }}</span>
            </div>
        </div>

        <div class="total-section">
            <span class="total-label">الإجمالي المدفوع</span>
            <span class="total-amount">{{ number_format($invoice->amount, 2) }} ج.م</span>
        </div>

        <div class="barcode">
            |||| | || ||| || ||| |<br>
            {{ $invoice->id . time() }}
        </div>

        <div class="footer">
            <p>نتمنى لكم الشفاء العاجل</p>
            <p>برمج بواسطة: Eng / Mohamed Anas </p>
        </div>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn btn-print">طباعة الآن</button>
        <a href="{{ route('patient.patients') }}" class="btn btn-back">العودة للقائمة</a>
    </div>

</body>

</html>
