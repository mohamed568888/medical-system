<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // تحديد الحقول المسموح بتخزينها مباشرة
    protected $fillable = [
        'patient_id',
        'clinic_id',
        'amount',
        'type',
        'notes',
        'status',
    ];

    // علاقة الفاتورة بالمريض
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // علاقة الفاتورة بالعيادة (إذا كنت تستخدمها)
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
