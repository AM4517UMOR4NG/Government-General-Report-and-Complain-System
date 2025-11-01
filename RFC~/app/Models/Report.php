<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_no',
        'queue_no',
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

        static::creating(function ($report) {
            if (empty($report->ticket_no)) {
                // Generate ticket number with date prefix for better tracking
                $datePrefix = now()->format('Ymd');
                $randomSuffix = strtoupper(Str::random(6));
                $report->ticket_no = 'RPT-' . $datePrefix . '-' . $randomSuffix;
            }
            
            // Calculate SLA due date based on priority
            $report->sla_due_at = $report->calculateSLADueDate();
        });

        static::updating(function ($report) {
            $report->last_activity_at = now();
        });
    }

    /**
     * Generate next queue number for today, format: Q-YYYYMMDD-####
     */
    public static function nextQueueNo(): string
    {
        $date = now()->format('Ymd');
        $countToday = static::whereDate('created_at', now()->toDateString())
            ->whereNotNull('queue_no')
            ->count();
        $seq = str_pad((string)($countToday + 1), 4, '0', STR_PAD_LEFT);
        return 'Q-' . $date . '-' . $seq;
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
