<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'workshop_id',
        'assigned_to',
        'assigned_by',
        'due_date',
        'status',
        'priority',
        'completion_notes',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => 'text-red-600 bg-red-100',
            'medium' => 'text-yellow-600 bg-yellow-100',
            'low' => 'text-green-600 bg-green-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'completed' => 'text-green-600 bg-green-100',
            'in_progress' => 'text-blue-600 bg-blue-100',
            'pending' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }
}