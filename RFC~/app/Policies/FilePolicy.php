<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view files.
     */
    public function view(User $user, $reportable)
    {
        \Log::info('FilePolicy::view', [
            'user_id' => $user->id, 
            'role' => $user->role, 
            'email' => $user->email, 
            'reportable_id' => $reportable->id, 
            'assigned_to' => $reportable->assigned_to ?? 'null',
            'reportable_department_id' => $reportable->department_id ?? 'null',
            'user_department_id' => $user->department_id ?? 'null'
        ]);
        
        // Admin can view all files
        if ($user->role === 'admin') {
            return true;
        }
        
        // Department head can view files in their department
        if ($user->role === 'department_head' && $reportable->department_id === $user->department_id) {
            return true;
        }
        
        // Staff can view files if:
        // 1. Report is in their department, OR
        // 2. Report is assigned to them, OR
        // 3. Report is in submitted/pending status in their department
        if ($user->role === 'staff') {
            if ($reportable->department_id === $user->department_id || 
                $reportable->assigned_to === $user->id ||
                (in_array($reportable->status, ['submitted', 'pending', 'verified']) && $reportable->department_id === $user->department_id)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can download files.
     */
    public function download(User $user, $reportable)
    {
        \Log::info('FilePolicy::download', [
            'user_id' => $user->id, 
            'role' => $user->role, 
            'email' => $user->email, 
            'reportable_id' => $reportable->id, 
            'assigned_to' => $reportable->assigned_to ?? 'null',
            'reportable_department_id' => $reportable->department_id ?? 'null',
            'user_department_id' => $user->department_id ?? 'null'
        ]);
        
        // Admin can download all files
        if ($user->role === 'admin') {
            return true;
        }
        
        // Department head can download files in their department
        if ($user->role === 'department_head' && $reportable->department_id === $user->department_id) {
            return true;
        }
        
        // Staff can download files if:
        // 1. Report is in their department, OR
        // 2. Report is assigned to them, OR
        // 3. Report is in submitted/pending status in their department
        if ($user->role === 'staff') {
            if ($reportable->department_id === $user->department_id || 
                $reportable->assigned_to === $user->id ||
                (in_array($reportable->status, ['submitted', 'pending', 'verified']) && $reportable->department_id === $user->department_id)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can preview files.
     */
    public function preview(User $user, $reportable)
    {
        \Log::info('FilePolicy::preview', ['user_id' => $user->id, 'role' => $user->role, 'email' => $user->email]);
        if ($user->role === 'admin') {
            return true;
        }
        return $this->view($user, $reportable);
    }
}
