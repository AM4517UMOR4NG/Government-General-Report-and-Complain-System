<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'phone',
        'address',
        'id_number',
        'is_active',
        'avatar',
        'birth_date',
        'gender',
        'employee_id',
        'position',
        'bio',
        'settings',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'settings' => 'array',
        'last_login_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function assignedReports()
    {
        return $this->hasMany(Report::class, 'assigned_to');
    }

    public function assignedComplaints()
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDepartmentHead()
    {
        return $this->role === 'department_head';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isCitizen()
    {
        return $this->role === 'citizen';
    }

    /**
     * Get user's avatar URL
     */
    public function getAvatarUrl()
    {
        if (!$this->avatar) {
            return null;
        }

        // Use Laravel's Storage abstraction to build the URL reliably
        try {
            return Storage::disk('public')->url($this->avatar);
        } catch (\Exception $e) {
            // Fallback to manual URL construction if Storage fails
            return asset('storage/' . $this->avatar);
        }
    }

    /**
     * Get avatar initials for default display
     */
    public function getAvatarInitials()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            if (strlen($initials) >= 2) break;
        }
        
        return $initials ?: strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get avatar background color based on name
     */
    public function getAvatarColor()
    {
        $colors = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', 
            '#e74a3b', '#858796', '#5a5c69', '#6f42c1',
            '#fd7e14', '#20c997', '#6610f2', '#e83e8c'
        ];
        
        $index = ord(strtolower($this->name[0])) % count($colors);
        return $colors[$index];
    }

    /**
     * Get user's full name with position
     */
    public function getFullNameWithPosition()
    {
        $name = $this->name;
        if ($this->position) {
            $name .= ' (' . $this->position . ')';
        }
        return $name;
    }

    /**
     * Get user's settings with defaults
     */
    public function getSettings($key = null, $default = null)
    {
        $defaultSettings = [
            'dashboard_layout' => 'comfortable',
            'items_per_page' => 15,
            'language' => 'id',
            'notifications' => [
                'email' => true,
                'browser' => true,
                'sms' => false,
                'reports' => true,
                'complaints' => true,
                'status' => true,
            ],
            'privacy' => [
                'show_email' => false,
                'show_phone' => false,
                'show_address' => false,
            ],
        ];

        $settings = is_array($this->settings) ? $this->settings : [];
        $mergedSettings = array_replace_recursive($defaultSettings, $settings);

        if ($key) {
            return data_get($mergedSettings, $key, $default);
        }

        return $mergedSettings;
    }

    /**
     * Update user settings
     */
    public function updateSettings(array $newSettings)
    {
        $currentSettings = $this->getSettings();
        $this->settings = array_replace_recursive($currentSettings, $newSettings);
        $this->save();
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName()
    {
        $roles = [
            'admin' => 'Administrator',
            'department_head' => 'Kepala Departemen',
            'staff' => 'Staff',
            'citizen' => 'Warga',
        ];

        return $roles[$this->role] ?? ucfirst($this->role);
    }
}
