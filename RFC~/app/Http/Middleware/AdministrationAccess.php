<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministrationAccess
{
    /**
     * Handle an incoming request.
     * Allows users with role department_head OR staff.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        if (!($user->isDepartmentHead() || $user->isStaff())) {
            abort(403, 'Access denied. Administration privileges required.');
        }

        return $next($request);
    }
}


