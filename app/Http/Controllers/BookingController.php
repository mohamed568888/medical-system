<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Clinic;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * 1. عرض صفحة الحجز للمرضى (الواجهة العامة)
     */
    public function showForm()
    {
        // جلب جميع العيادات ليعرضها المريض في القائمة
        $clinics = Clinic::all();

        // تأكد أن ملف العرض موجود في resources/views/book.blade.php
        return view('book', compact('clinics'));
    }

    /**
     * 2. استقبال بيانات الحجز من المريض وحفظها
     */
    public function submit(Request $request)
    {
        // التحقق من صحة البيانات الواردة
        $request->validate([
            'name'       => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender'     => 'required|in:male,female',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'clinic_id'  => 'required|exists:clinics,id',
        ]);

        // إنشاء سجل مريض جديد (الذي يمثل الحجز)
        $newPatient = Patient::create([
            'name'       => $request->name,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,
            'phone'      => $request->phone,
            'address'    => $request->address,
            'clinic_id'  => $request->clinic_id,
        ]);

        // جلب بيانات المريض مع اسم العيادة لإرسال إشعار دقيق
        $patientData = Patient::with('clinic')->find($newPatient->id);

        // إرسال إشعار لجميع المديرين (Admins) بوجود حجز جديد
        $admins = User::all();
        foreach ($admins as $admin) {
            $admin->notify(new NewBookingNotification($patientData));
        }

        // العودة مع رسالة نجاح بصيغة 'success' لتعمل مع الـ SweetAlert
        return back()->with('success', 'شكراً لك! تم استلام طلبك بنجاح، وسيتواصل معك فريق العيادة قريباً لتأكيد الموعد.');
    }

    /**
     * 3. عرض قائمة الحجوزات داخل لوحة التحكم (للموظفين)
     */
    public function showBooked()
    {
        // جلب المرضى مرتبين من الأحدث للأقدم
        $patients = Patient::with('clinic')->latest()->get();
        $patientsCount = Patient::count();

        return view('booking.booked', compact('patients', 'patientsCount'));
    }

    /**
     * 4. حذف حجز (مريض) من النظام
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        // رسالة نجاح لعملية الحذف
        return back()->with('success', 'تم حذف بيانات الحجز بنجاح.');
    }

    /**
     * 5. عرض سجل المرضى بالكامل (Dashboard General View)
     */
    public function showAllPatients()
    {
        $patients = Patient::with('clinic')->latest()->paginate(15);
        return view('patient.all', compact('patients'));
    }
}