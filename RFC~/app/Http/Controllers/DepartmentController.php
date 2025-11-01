<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::withCount(['users', 'reports', 'complaints'])->get();
        return view('admin.departments', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Department::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.departments')->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::with(['users', 'reports', 'complaints'])->findOrFail($id);
        return view('admin.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $department->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.departments')->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        
        // Check if department has users, reports, or complaints
        if ($department->users()->count() > 0 || $department->reports()->count() > 0 || $department->complaints()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus departemen yang masih memiliki data terkait.');
        }
        
        $department->delete();
        return redirect()->route('admin.departments')->with('success', 'Departemen berhasil dihapus.');
    }

    /**
     * API endpoint for departments
     */
    public function apiIndex()
    {
        $departments = Department::where('is_active', true)->select('id', 'name', 'code')->get();
        return response()->json($departments);
    }
}
