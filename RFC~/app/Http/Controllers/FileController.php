<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    /**
     * View uploaded files for a report or complaint
     */
    public function viewReportFiles($type, $id)
    {
        if ($type === 'report') {
            $reportable = Report::findOrFail($id);
        } else {
            $reportable = Complaint::findOrFail($id);
        }
        
        // Check if user has permission to view files
        Gate::authorize('view', $reportable);

        $files = $this->getFileDetails($reportable->attachments ?? []);
        
        return view('admin.files.view', [
            'reportable' => $reportable,
            'files' => $files,
            'type' => $type
        ]);
    }


    /**
     * Download a specific file
     */
    public function downloadFile(Request $request, $type, $id, $filename)
    {
        try {
            $reportable = $type === 'report' ? Report::findOrFail($id) : Complaint::findOrFail($id);
            
            // Debug: Log user information
            \Log::info('File Download Attempt', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email ?? 'not logged in',
                'user_role' => Auth::user()->role ?? 'no role',
                'reportable_id' => $reportable->id,
                'reportable_type' => get_class($reportable),
                'reportable_department_id' => $reportable->department_id,
                'filename' => $filename
            ]);
            
            // Check if user has permission to download files
            Gate::authorize('download', $reportable);

            $filePath = $this->getFilePath($reportable, $filename);
            
            if (!$filePath || !Storage::exists($filePath)) {
                abort(404, 'File not found.');
            }

            return Storage::download($filePath, $filename);
        } catch (\Exception $e) {
            \Log::error('File Download Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role
                ] : 'not logged in'
            ], 403);
        }
    }

    /**
     * Preview an image file
     */
    public function previewImage(Request $request, $type, $id, $filename)
    {
        $reportable = $type === 'report' ? Report::findOrFail($id) : Complaint::findOrFail($id);
        
        // Check if user has permission to view files
        Gate::authorize('preview', $reportable);

        $filePath = $this->getFilePath($reportable, $filename);
        
        if (!$filePath || !Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Check if file is an image
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($extension, $imageExtensions)) {
            abort(400, 'File is not an image.');
        }

        $file = Storage::get($filePath);
        $mimeType = Storage::mimeType($filePath);

        return response($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    /**
     * Download all files as ZIP
     */
    public function downloadAllFiles(Request $request, $type, $id)
    {
        $reportable = $type === 'report' ? Report::findOrFail($id) : Complaint::findOrFail($id);
        
        // Check if user has permission to download files
        Gate::authorize('download', $reportable);

        $files = $reportable->attachments ?? [];
        
        if (empty($files)) {
            return redirect()->back()->with('error', 'No files to download.');
        }

        // Check if ZipArchive is available
        if (!class_exists('ZipArchive')) {
            return $this->downloadFilesAsList($reportable, $files);
        }

        try {
            $zip = new \ZipArchive();
            $zipPath = storage_path('app/temp/' . $reportable->ticket_no . '_files_' . time() . '.zip');
            
            if (!is_dir(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0755, true);
            }

            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                return redirect()->back()->with('error', 'Failed to create archive.');
            }

            foreach ($files as $file) {
                $fullPath = storage_path('app/public/' . $file);
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, basename($file));
                }
            }

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Zip creation failed: ' . $e->getMessage());
            return $this->downloadFilesAsList($reportable, $files);
        }
    }

    /**
     * Download files as a list (fallback when ZipArchive is not available)
     */
    private function downloadFilesAsList($reportable, $files)
    {
        $fileList = [];
        
        foreach ($files as $file) {
            $fullPath = storage_path('app/public/' . $file);
            if (file_exists($fullPath)) {
                $fileList[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => filesize($fullPath),
                    'url' => route('files.download', [$reportable instanceof Report ? 'report' : 'complaint', $reportable->id, basename($file)])
                ];
            }
        }

        $filename = $reportable->ticket_no . '_files_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json([
            'ticket_no' => $reportable->ticket_no,
            'title' => $reportable->title,
            'files' => $fileList,
            'total_files' => count($fileList),
            'download_instructions' => 'Download individual files using the URLs provided'
        ])
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->header('Content-Type', 'application/json');
    }


    /**
     * Get file details with metadata
     */
    private function getFileDetails($attachments)
    {
        $files = [];
        
        foreach ($attachments as $file) {
            $fullPath = storage_path('app/public/' . $file);
            
            if (file_exists($fullPath)) {
                $fileInfo = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => filesize($fullPath),
                    'size_formatted' => $this->formatFileSize(filesize($fullPath)),
                    'extension' => strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                    'mime_type' => mime_content_type($fullPath),
                    'is_image' => $this->isImageFile($file),
                    'created_at' => date('Y-m-d H:i:s', filemtime($fullPath))
                ];
                
                $files[] = $fileInfo;
            }
        }
        
        return $files;
    }

    /**
     * Get file path for download
     */
    private function getFilePath($reportable, $filename)
    {
        $attachments = $reportable->attachments ?? [];
        
        foreach ($attachments as $file) {
            if (basename($file) === $filename) {
                return 'public/' . $file;
            }
        }
        
        return null;
    }

    /**
     * Check if file is an image
     */
    private function isImageFile($filename)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        return in_array($extension, $imageExtensions);
    }

    /**
     * Format file size
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
