<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // التأكد من أن الدكتور اختار عيادة
        if ($request->has('clinic_id')) {
            session(['active_clinic_id' => $request->clinic_id]);
        } else {
            // خيار احتياطي: إذا لم يختار، نأخذ أول عيادة في النظام
            $firstClinic = \App\Models\Clinic::first();
            if ($firstClinic) {
                session(['active_clinic_id' => $firstClinic->id]);
            }
        }

        // التوجيه للداشبورد
        return redirect()->route('dashboard');
    }
}
