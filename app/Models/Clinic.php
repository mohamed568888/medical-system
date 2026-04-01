<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    use HasFactory;
    // الحقول المسموح بحفظها دفعة واحدة
    protected $fillable = [
        'name',
        'address',
        'user_id',
    ];
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
