<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class DisableEmailInDevelopment
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Disable email in development environment
        if (app()->environment('local', 'development')) {
            Mail::fake();
        }

        return $next($request);
    }
}