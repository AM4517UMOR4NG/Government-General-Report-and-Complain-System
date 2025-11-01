<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileAccessControl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Check if user has file access permissions
        if (!in_array($user->role, ['admin', 'staff', 'department_head'])) {
            abort(403, 'You do not have permission to access files.');
        }
        
        return $next($request);
    }
}
