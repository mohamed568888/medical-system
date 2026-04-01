<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    // عرض كل عيادات المركز لجميع الدكاترة
    public function index()
    {
        // جلب كل العيادات الموجودة في المركز لجميع الدكاترة
        $clinics = \App\Models\Clinic::withCount('patients')->latest()->get();

        return view('clinic.index', compact('clinics'));
    }

    public function create()
    {
        return view('clinic.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $data['user_id'] = Auth::id(); // تخزين من قام بإضافة العيادة للتوثيق فقط
        $clinic = Clinic::create($data);

        return redirect()->route('clinic.index')->with('success', 'تم إضافة عيادة جديدة للمركز!');
    }

    // صفحة التعديل (متاحة لأي دكتور)
    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        return view('clinic.edit', compact('clinic'));
    }

    // تحديث بيانات العيادة
    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $clinic->update($data);
        return redirect()->route('clinic.index')->with('success', 'تم تحديث بيانات العيادة بنجاح');
    }

    // حذف العيادة من السستم
    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->delete();
        return redirect()->back()->with('success', 'تم حذف العيادة من المركز بنجاح');
    }
}
