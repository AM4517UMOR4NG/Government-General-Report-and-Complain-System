<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any reports.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'department_head', 'staff']);
    }

    /**
     * Determine whether the user can view the report.
     */
    public function view(User $user, Report $report)
    {
        // User can view their own reports
        if ($report->user_id === $user->id) {
            return true;
        }

        // Admin can view all reports
        if ($user->role === 'admin') {
            return true;
        }

        // Department head can view reports in their department
        if ($user->role === 'department_head' && $report->department_id === $user->department_id) {
            return true;
        }

        // Staff can view reports assigned to them
        if ($user->role === 'staff' && $report->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create reports.
     */
    public function create(User $user)
    {
        return $user->role === 'citizen';
    }

    /**
     * Determine whether the user can update the report.
     */
    public function update(User $user, Report $report)
    {
        // User can update their own reports if not yet verified
        if ($report->user_id === $user->id && in_array($report->status, ['submitted'])) {
            return true;
        }

        // Admin can update any report
        if ($user->role === 'admin') {
            return true;
        }

        // Department head can update reports in their department
        if ($user->role === 'department_head' && $report->department_id === $user->department_id) {
            return true;
        }

        // Staff can update reports assigned to them
        if ($user->role === 'staff' && $report->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the report.
     */
    public function delete(User $user, Report $report)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can verify the report.
     */
    public function verify(User $user, Report $report)
    {
        return in_array($user->role, ['admin', 'department_head']) && 
               $report->status === 'submitted';
    }

    /**
     * Determine whether the user can assign the report.
     */
    public function assign(User $user, Report $report)
    {
        return in_array($user->role, ['admin', 'department_head']) && 
               in_array($report->status, ['verified', 'assigned']);
    }

    /**
     * Determine whether the user can resolve the report.
     */
    public function resolve(User $user, Report $report)
    {
        return ($user->role === 'staff' && $report->assigned_to === $user->id) ||
               ($user->role === 'department_head' && $report->department_id === $user->department_id) ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can approve the report.
     */
    public function approve(User $user, Report $report)
    {
        return ($user->role === 'department_head' && $report->department_id === $user->department_id) ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can reopen the report.
     */
    public function reopen(User $user, Report $report)
    {
        return $report->canBeReopened() && 
               ($report->user_id === $user->id || in_array($user->role, ['admin', 'department_head']));
    }
}
