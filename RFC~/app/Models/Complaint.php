<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_no',
        'title',
        'description',
        'category',
        'status',
        'priority',
        'user_id',
        'department_id',
        'assigned_to',
        'location',
        'attachments',
        'investigation_notes',
        'resolution_notes',
        'resolved_at',
        'sla_due_at',
        'is_escalated',
        'reassign_count',
        'last_activity_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
        'sla_due_at' => 'datetime',
        'is_escalated' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($complaint) {
            if (empty($complaint->ticket_no)) {
                $complaint->ticket_no = 'CMP-' . strtoupper(Str::random(8));
            }
            
            // Calculate SLA due date based on priority
            $complaint->sla_due_at = $complaint->calculateSLADueDate();
        });

        static::updating(function ($complaint) {
            $complaint->last_activity_at = now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignments()
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function calculateSLADueDate()
    {
        $slaHours = [
            'urgent' => 2,
            'high' => 8,
            'medium' => 24,
            'low' => 72,
        ];

        $hours = $slaHours[$this->priority] ?? 24;
        return now()->addHours($hours);
    }

    public function isSLABreached()
    {
        return $this->sla_due_at && now()->isAfter($this->sla_due_at);
    }

    public function canBeReopened()
    {
        return $this->status === 'closed' && 
               $this->resolved_at && 
               $this->resolved_at->diffInDays(now()) <= 30;
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'verified']);
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress', 'awaiting_info']);
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    public function scopeEscalated($query)
    {
        return $query->where('is_escalated', true);
    }
}
