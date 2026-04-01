<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    /**
     * حفظ روشتة جديدة للمريض
     */
    public function store(Request $request)
    {
        // 1. التحقق من البيانات
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicine'   => 'required|string',
            'notes'      => 'nullable|string',
        ]);

        // 2. إنشاء الروشتة في الداتابيز
        Prescription::create([
            'patient_id' => $request->patient_id,
            'medicine'   => $request->medicine,
            'notes'      => $request->notes,
            'date'       => now()->format('Y-m-d'), // تسجيل تاريخ اليوم تلقائياً
        ]);

        // 3. الرجوع لصفحة المريض مع رسالة نجاح
        return redirect()->route('patient.show', $request->patient_id)
            ->with('success', 'تم إضافة الروشتة بنجاح وجاهزة للطباعة.');
    }

    /**
     * عرض صفحة الطباعة لروشتة معينة
     */
    public function print($id)
    {
        // جلب الروشتة مع بيانات المريض والعيادة (Eager Loading) لتحسين الأداء
        $prescription = Prescription::with('patient.clinic')->findOrFail($id);

        return view('prescriptions.print', compact('prescription'));
    }

    /**
     * حذف روشتة قديمة (اختياري)
     */
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patientId = $prescription->patient_id;
        $prescription->delete();

        return redirect()->route('patient.show', $patientId)
            ->with('success', 'تم حذف الروشتة من السجل.');
    }
}
