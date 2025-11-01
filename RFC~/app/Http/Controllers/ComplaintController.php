<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complaints = Complaint::with(['user', 'department', 'assignedUser'])
            ->latest()
            ->paginate(20);

        return view('admin.complaints', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('citizen.complaints.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'location' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('public/attachments/complaints');
                $attachments[] = str_replace('public/', '', $path);
            }
        }

        $complaint = Complaint::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'department_id' => $request->department_id,
            'location' => $request->location,
            'priority' => $request->priority,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'attachments' => $attachments ?: null,
        ]);

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Keluhan berhasil dikirim');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $complaint = Complaint::with(['user', 'department', 'assignedUser'])->findOrFail($id);
        
        // Check if user can view this complaint
        if (Auth::user()->isCitizen() && $complaint->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to complaint.');
        }
        
        return view('citizen.complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $departments = Department::all();
        $staff = User::where('role', 'staff')->get();
        return view('admin.complaints_edit', compact('complaint', 'departments', 'staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update($request->only([
            'title', 'description', 'category', 'status', 'priority', 'department_id', 'assigned_to', 'location'
        ]));
        return redirect()->route('admin.complaints')->with('success', 'Keluhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();
        return redirect()->route('admin.complaints')->with('success', 'Keluhan berhasil dihapus.');
    }

    /**
     * API endpoint for complaint statistics
     */
    public function stats()
    {
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'investigating' => Complaint::where('status', 'investigating')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'by_priority' => Complaint::selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->get()
                ->pluck('count', 'priority'),
            'by_category' => Complaint::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->get()
                ->pluck('count', 'category'),
        ];

        return response()->json($stats);
    }
}
