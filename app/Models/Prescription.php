<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_name',
        'medication_name',
        'dosage',
        'instructions',
        'prescribed_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}