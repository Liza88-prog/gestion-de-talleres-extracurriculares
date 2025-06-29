<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'instructor',
        'capacity',
        'start_date',
        'end_date',
        'schedule',
        'location',
        'status',
        'image',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'schedule' => 'array',
    ];

    public function students()
    {
        return $this->belongsToMany(User::class, 'workshop_enrollments')
                    ->where('role', 'student')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function enrollments()
    {
        return $this->hasMany(WorkshopEnrollment::class);
    }

    public function getEnrolledCountAttribute()
    {
        return $this->students()->count();
    }

    public function getAvailableSpotsAttribute()
    {
        return $this->capacity - $this->enrolled_count;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}