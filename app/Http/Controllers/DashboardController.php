<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeClinic = null;
        if (session()->has('active_clinic_id')) {
            $activeClinic = \App\Models\Clinic::find(session('active_clinic_id'));
        }

        // جلب الإحصائيات العامة للمركز
        $clinicsCount = \App\Models\Clinic::count();
        $patientsCount = \App\Models\Patient::count();
        $usersCount = \App\Models\User::count();

        return view('dashboard', compact('activeClinic', 'clinicsCount', 'patientsCount', 'usersCount'));
    }
}
