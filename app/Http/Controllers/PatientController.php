<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    /**
     * عرض قائمة المرضى (الشاملة + المفلترة)
     */
    public function patients(Request $request)
    {
        $clinics = Clinic::all();
        $query = Patient::with('clinic');

        // 1. إذا كان المستخدم قد اختار عيادة محددة من القائمة
        if ($request->filled('clinic_id') && $request->clinic_id != 'all') {
            $query->where('clinic_id', $request->clinic_id);
        }
        // 2. إذا اختار المستخدم "عرض جميع العيادات" صراحةً
        elseif ($request->clinic_id == 'all') {
            // لا نفعل شيء، نترك الاستعلام يجلب الجميع
        }
        // 3. الحالة الافتراضية: إذا لم يقم المستخدم بالبحث أو التصفية بعد
        elseif (session()->has('active_clinic_id')) {
            $query->where('clinic_id', session('active_clinic_id'));
        }

        // منطق البحث (يبقى كما هو)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(10);

        return view('patient.patients', compact('patients', 'clinics'));
    }

    public function show($id)
    {
        $patient = Patient::with('clinic')->findOrFail($id);
        return view('patient.show', compact('patient'));
    }

    public function create()
    {
        // جلب العيادات ليختار منها المستخدم عند التسجيل
        $clinics = Clinic::all();
        return view('patient.create', compact('clinics'));
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        $clinics = Clinic::all();

        return view('patient.edit', compact('patient', 'clinics'));
    }

    public function store(Request $request)
    {
        // 1. التحقق من البيانات (إضافة حقول الدفع للـ Validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'clinic_id' => 'required|exists:clinics,id',
            'phone' => 'required',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'payment_amount' => 'nullable|numeric|min:0', // مبلغ الدفع اختياري
            'payment_type' => 'nullable|string',
        ]);

        // 2. تجهيز بيانات المريض واستبعاد حقول الدفع والملفات من المصفوفة الأساسية
        $data = $request->except(['_token', 'documents', 'payment_amount', 'payment_type']);
        $files = [];

        // رفع الملفات
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('patients_docs', 'public');
                $files[] = $path;
            }
        }
        $data['document'] = $files;

        // 3. إنشاء المريض وحفظه في متغير لنحصل على الـ ID بتاعه
        $patient = Patient::create($data);

        // 4. منطق الدفع: إذا تم إدخال مبلغ، سجل فاتورة فوراً
        if ($request->filled('payment_amount') && $request->payment_amount > 0) {
            $invoice = \App\Models\Invoice::create([
                'patient_id' => $patient->id,
                'clinic_id'  => $request->clinic_id,
                'amount'     => $request->payment_amount,
                'type'       => $request->payment_type ?? 'كشف', // افتراضي كشف لو مختارش
                'status'     => 'paid',
                'notes'      => 'تم التحصيل عند تسجيل المريض الجديد',
            ]);

            // 5. التوجيه لصفحة طباعة الوصل (Receipt)
            return redirect()->route('finance.print', $invoice->id)
                ->with('success', 'تم تسجيل المريض وتحصيل المبلغ بنجاح');
        }

        // إذا لم يوجد دفع، ارجع لصفحة المرضى العادية
        return redirect()->route('patient.patients')->with('success', 'تم تسجيل المريض بنجاح');
    }

    public function update(Request $request)
    {
        $patient = Patient::findOrFail($request->old_id);

        // جلب الملفات القديمة كمصفوفة (بفضل الـ Casts)
        // نستخدم (array) لضمان أنها مصفوفة حتى لو كانت null في القاعدة
        $existingDocs = (array) $patient->document;

        // إضافة الملفات الجديدة للمصفوفة الحالية
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('patients_docs', 'public');
                $existingDocs[] = $path;
            }
        }

        $patient->update([
            'name'             => $request->name,
            'clinic_id'        => $request->clinic_id,
            'phone'            => $request->phone,
            'birth_date'       => $request->birth_date,
            'Diagnosis'        => $request->Diagnosis,
            'Chronic_diseases' => $request->Chronic_diseases,
            'document'         => $existingDocs, // نرسل المصفوفة كاملة للموديل
        ]);

        return redirect()->route('patient.show', $patient->id)->with('success', 'تم تحديث بيانات المريض بنجاح');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

        // تحويل المرفقات لمصفوفة للتأكد من إمكانية عمل Loop
        $docs = (array) $patient->document;

        foreach ($docs as $file) {
            // حذف كل ملف من التخزين (Storage)
            if (Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        $patient->delete();

        return redirect()->route('patient.patients')->with('success', 'تم حذف المريض وجميع مرفقاته بنجاح');
    }

    public function deleteFile(Request $request)
    {
        $patient = Patient::findOrFail($request->patient_id);
        $filePath = $request->file_path;

        // 1. التأكد أن الملف موجود في مصفوفة المريض
        $documents = $patient->document;

        if (($key = array_search($filePath, $documents)) !== false) {
            // 2. حذف الملف من التخزين الفيزيائي (Storage)
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // 3. إزالة الملف من المصفوفة وتحديث القاعدة
            unset($documents[$key]);
            $patient->document = array_values($documents); // إعادة ترتيب المفاتيح
            $patient->save();

            return back()->with('success', 'تم حذف الملف بنجاح');
        }

        return back()->with('error', 'عذراً، لم يتم العثور على الملف');
    }
}
