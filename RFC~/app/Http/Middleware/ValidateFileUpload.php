<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateFileUpload
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('attachments')) {
            $allowedMimes = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip'
            ];

            $maxSize = 5 * 1024; // 5MB in KB

            foreach ($request->file('attachments') as $file) {
                // Check file size
                if ($file->getSize() > $maxSize * 1024) {
                    return response()->json([
                        'message' => 'File size too large. Maximum size is 5MB.',
                    ], 422);
                }

                // Check MIME type
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return response()->json([
                        'message' => 'File type not allowed. Allowed types: JPG, PNG, GIF, WEBP, PDF, DOC, DOCX, XLS, XLSX, ZIP.',
                    ], 422);
                }

                // Check for malicious file extensions
                $extension = strtolower($file->getClientOriginalExtension());
                $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
                
                if (in_array($extension, $dangerousExtensions)) {
                    return response()->json([
                        'message' => 'File type not allowed for security reasons.',
                    ], 422);
                }
            }
        }

        return $next($request);
    }
}
