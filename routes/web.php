<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\FinanceController;

require __DIR__ . '/auth.php';

// --- 1. الروابط العامة ---
Auth::routes(['register' => false]);

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/book', [BookingController::class, 'showForm'])->name('book');
Route::post('/book/submit', [BookingController::class, 'submit'])->name('book.submit');

// --- 2. الروابط المحمية ---
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // مسار تبديل العيادة النشطة (وضعتُه هنا ليكون مباشراً وسهلاً)
    // الرابط سيكون: yoursite.com/select-clinic/{id}
    Route::get('/select-clinic/{id}', function ($id) {
        if (\App\Models\Clinic::where('id', $id)->exists()) {
            session(['active_clinic_id' => $id]);
            return back()->with('success', 'Clinic switched successfully');
        }
        return back()->with('error', 'Clinic not found');
    })->name('clinic.switch');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');

    // --- الإشعارات ---
    Route::get('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

    // --- إدارة المستخدمين ---
    Route::prefix('user')->group(function () {
        Route::get('s', [UserController::class, 'users'])->name('user.users');
        Route::get('create', [UserController::class, 'create'])->name('user.create');
        Route::post('store', [UserController::class, 'store'])->name('user.store');
        Route::get('show/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('update', [UserController::class, 'update'])->name('user.update');
        Route::get('delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    });

    // --- سجل المرضى العام ---
    Route::prefix('patient')->group(function () {
        Route::get('s', [PatientController::class, 'patients'])->name('patient.patients');
        Route::get('all', [BookingController::class, 'showAllPatients'])->name('patients.all');
        Route::get('create', [PatientController::class, 'create'])->name('patient.create');
        Route::post('store', [PatientController::class, 'store'])->name('patient.store');
        Route::get('show/{id}', [PatientController::class, 'show'])->name('patient.show');
        Route::get('edit/{id}', [PatientController::class, 'edit'])->name('patient.edit');
        Route::post('update', [PatientController::class, 'update'])->name('patient.update');
        Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patient.destroy');
        Route::delete('delete/{id}', [BookingController::class, 'destroy'])->name('patient.delete');
        Route::get('patient/s', [PatientController::class, 'patients'])->name('patient.patients');
        Route::post('/patient/delete-file', [App\Http\Controllers\PatientController::class, 'deleteFile'])->name('patient.deleteFile');
    });

    // --- نظام الحجوزات ---
    Route::get('/booked-list', [BookingController::class, 'showBooked'])->name('booking.booked');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');

    // --- إدارة العيادات ---
    Route::prefix('clinics')->group(function () {
        Route::get('/', [ClinicController::class, 'index'])->name('clinic.index');
        Route::get('/create', [ClinicController::class, 'create'])->name('clinic.create');
        Route::post('/store', [ClinicController::class, 'store'])->name('clinic.store');
        Route::get('/{id}/edit', [ClinicController::class, 'edit'])->name('clinic.edit');
        Route::put('/{id}', [ClinicController::class, 'update'])->name('clinic.update');
        Route::delete('/{id}', [ClinicController::class, 'destroy'])->name('clinic.destroy');
    });

    // مسارات الروشتات
    Route::post('/prescriptions/store', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('/prescriptions/print/{id}', [PrescriptionController::class, 'print'])->name('prescriptions.print');
    Route::delete('/prescriptions/delete/{id}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
});


//مسارات الدفع و الفلوس 
Route::prefix('finance')->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/store', [FinanceController::class, 'store'])->name('finance.store');
    Route::delete('/destroy/{id}', [FinanceController::class, 'destroy'])->name('finance.destroy');
    Route::get('/finance/print/{id}', [FinanceController::class, 'printReceipt'])->name('finance.print');
});
