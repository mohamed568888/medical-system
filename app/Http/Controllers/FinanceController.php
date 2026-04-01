<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    // 1. الصفحة الرئيسية للحسابات (لوحة البيانات المالية)
    public function index(Request $request)
    {
        $query = Invoice::query();

        // فلترة العيادة
        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // فلترة التاريخ
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $invoices = $query->with('clinic', 'patient')->get();

        // --- الجزء الأهم للرسم البياني ---
        // تجميع المبالغ حسب اسم العيادة
        $chartData = $invoices->groupBy('clinic.name')->map(function ($group) {
            return $group->sum('amount');
        });

        return view('finance.index', [
            'invoices' => $invoices,
            'clinics'  => Clinic::all(),
            'labels'   => $chartData->keys(),   // أسماء العيادات
            'amounts'  => $chartData->values(), // المبالغ لكل عيادة
        ]);
    }

    // 2. حفظ عملية دفع جديدة
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        // هنجيب أول عيادة متاحة في الداتا بيز عشان نضمن إن الـ ID صح
        $firstClinic = \App\Models\Clinic::first();

        if (!$firstClinic) {
            return redirect()->back()->with('error', 'يجب إضافة عيادة واحدة على الأقل أولاً');
        }

        \App\Models\Invoice::create([
            'patient_id' => $request->patient_id,
            'clinic_id'  => $firstClinic->id, // نستخدم ID لعيادة موجودة فعلاً
            'amount'     => $request->amount,
            'type'       => $request->type,
            'notes'      => $request->notes,
            'status'     => 'paid'
        ]);

        return redirect()->back()->with('success', 'تم تسجيل المبلغ بنجاح');
    }

    // 3. حذف عملية مالية (للإدارة فقط)
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return redirect()->back()->with('success', 'تم حذف السجل المالي');
    }

    public function printReceipt($id)
    {
        // جلب بيانات الفاتورة مع بيانات المريض والعيادة المرتبطة بها
        $invoice = Invoice::with(['patient.clinic'])->findOrFail($id);

        // العودة بصفحة الطباعة وتمرير بيانات الفاتورة لها
        return view('finance.print', compact('invoice'));
    }
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // لو المستخدم ممرض وبيحاول يدخل صفحة الحسابات (index)
            if (Auth::user()->role == 'nurse' && $request->routeIs('finance.index')) {
                abort(403, 'غير مسموح للممرض بالدخول لتقارير الحسابات');
            }
            return $next($request);
        });
    }
}
