<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
        'phone',
        'document',
        'Chronic_diseases',
        'Diagnosis',
        'birth_date',
        'gender',
        'address'
    ];
    public function clinic()
    {
        // دي بتقول لارفيل: روح هات بيانات العيادة اللي الـ ID بتاعها متخزن عندي
        return $this->belongsTo(Clinic::class);
    }
    protected $casts = [
        'document' => 'array',
    ];
    public function prescriptions()
    {
        // سيتم جلب الروشتات دائماً من الأحدث للأقدم
        return $this->hasMany(Prescription::class)->latest();
    }
}
