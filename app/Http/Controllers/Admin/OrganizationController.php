<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Division;
use App\Models\WorkUnit;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $divisions = Division::with('departments.workUnits', 'departments.users')->get();
        return view('admin.organization.index', compact('divisions'));
    }

    // ── DIVISION ──────────────────────────────────────────────
    public function storeDivision(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);
        $div = Division::create($data);
        AuditLogger::record('Organization', 'create', "Tambah divisi: {$div->name}");
        return back()->with('success', "Divisi {$div->name} berhasil ditambahkan.");
    }

    public function updateDivision(Request $request, Division $division)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);
        $division->update($data);
        AuditLogger::record('Organization', 'update', "Update divisi: {$division->name}");
        return back()->with('success', "Divisi berhasil diperbarui.");
    }

    public function destroyDivision(Division $division)
    {
        $name = $division->name;
        $division->delete();
        AuditLogger::record('Organization', 'delete', "Hapus divisi: {$name}");
        return back()->with('success', "Divisi {$name} berhasil dihapus.");
    }

    // ── DEPARTMENT ────────────────────────────────────────────
    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'division_id' => 'nullable|exists:divisions,id',
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);
        $dept = Department::create($data);
        AuditLogger::record('Organization', 'create', "Tambah departemen: {$dept->name}");
        return back()->with('success', "Departemen {$dept->name} berhasil ditambahkan.");
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $data = $request->validate([
            'division_id' => 'nullable|exists:divisions,id',
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);
        $department->update($data);
        AuditLogger::record('Organization', 'update', "Update departemen: {$department->name}");
        return back()->with('success', "Departemen berhasil diperbarui.");
    }

    public function destroyDepartment(Department $department)
    {
        $name = $department->name;
        $department->delete();
        AuditLogger::record('Organization', 'delete', "Hapus departemen: {$name}");
        return back()->with('success', "Departemen {$name} berhasil dihapus.");
    }

    // ── WORK UNIT ─────────────────────────────────────────────
    public function storeWorkUnit(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name'          => 'required|string|max:255',
            'code'          => 'nullable|string|max:20',
        ]);
        $wu = WorkUnit::create($data);
        AuditLogger::record('Organization', 'create', "Tambah unit kerja: {$wu->name}");
        return back()->with('success', "Unit kerja {$wu->name} berhasil ditambahkan.");
    }

    public function destroyWorkUnit(WorkUnit $workUnit)
    {
        $name = $workUnit->name;
        $workUnit->delete();
        AuditLogger::record('Organization', 'delete', "Hapus unit kerja: {$name}");
        return back()->with('success', "Unit kerja {$name} berhasil dihapus.");
    }
}
