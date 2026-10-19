<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('users')->orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:15|unique:departments',
            'name' => 'required|string|max:100',
        ], [
            'code.unique' => 'Kode divisi sudah digunakan, silakan pilih kode lain.',
        ]);

        Department::create([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return redirect()->route('departments.index')->with('success', 'Divisi baru berhasil ditambahkan.');
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:15', Rule::unique('departments')->ignore($department->id)],
            'name' => 'required|string|max:100',
        ], [
            'code.unique' => 'Kode divisi sudah digunakan.',
        ]);

        $department->update([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
        ]);

        return redirect()->route('departments.index')->with('success', 'Data divisi berhasil diperbarui.');
    }

    public function toggleStatus(Department $department): RedirectResponse
    {
        $department->update(['is_active' => !$department->is_active]);

        $status = $department->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Divisi berhasil {$status}.");
    }
}