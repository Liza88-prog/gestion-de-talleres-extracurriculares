<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workshop_id',
        'enrollment_date',
        'status',
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}