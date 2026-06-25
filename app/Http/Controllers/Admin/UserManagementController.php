<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\User;
use App\Models\WorkUnit;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // ── Index — sys_admin: full list dengan filter ─────────────
    public function index(Request $request)
    {
        $query = User::with('department.division', 'workUnit')->latest();

        if ($request->filled('role'))          { $query->where('role', $request->role); }
        if ($request->filled('department_id')) { $query->where('department_id', $request->department_id); }
        if ($request->filled('status'))        { $query->where('is_active', $request->status === 'active'); }
        if ($request->filled('search')) {
            $query->where(fn($q) => $q
                ->where('name', 'ilike', '%'.$request->search.'%')
                ->orWhere('email', 'ilike', '%'.$request->search.'%')
                ->orWhere('employee_id', 'ilike', '%'.$request->search.'%')
            );
        }

        $users       = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'departments'));
    }

    // ── User per Departemen — sys_admin, k3_manager, k3_officer ─
    // Digunakan k3_officer untuk assign form; k3_manager untuk overview
    public function byDepartment(Request $request)
    {
        $selectedDept = $request->input('department_id');

        $departments = Department::with([
            'division',
            'users' => fn($q) => $q->select('id','name','email','role','position','department_id','work_unit_id','is_active')
                                   ->orderBy('name'),
            'users.workUnit',
        ])->orderBy('name')->get();

        if ($selectedDept) {
            $departments = $departments->where('id', $selectedDept)->values();
        }

        $allDepartments = Department::orderBy('name')->get(); // untuk dropdown filter

        return view('admin.users.by-department', compact('departments', 'selectedDept', 'allDepartments'));
    }

    // ── Create — sys_admin ─────────────────────────────────────
    public function create()
    {
        $departments = Department::with('division')->orderBy('name')->get();
        $workUnits   = WorkUnit::orderBy('name')->get();
        return view('admin.users.form', ['user' => new User(), 'departments' => $departments, 'workUnits' => $workUnits]);
    }

    // ── Store — sys_admin ──────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'role'          => 'required|in:'.implode(',', array_keys(User::ROLES)),
            'employee_id'   => 'nullable|string|max:50|unique:users,employee_id',
            'phone'         => 'nullable|string|max:20',
            'position'      => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'work_unit_id'  => 'nullable|exists:work_units,id',
            'is_active'     => 'boolean',
        ]);

        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);
        AuditLogger::record('User', 'create', "Membuat akun: {$user->name} ({$user->role})");

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil dibuat.");
    }

    // ── Show (detail + activity log) — sys_admin ──────────────
    public function show(User $user)
    {
        $user->load('department.division', 'workUnit');
        $activityLogs = AuditLog::where('user_id', $user->id)->latest('created_at')->paginate(20);
        return view('admin.users.show', compact('user', 'activityLogs'));
    }

    // ── Edit — sys_admin ───────────────────────────────────────
    public function edit(User $user)
    {
        $departments = Department::with('division')->orderBy('name')->get();
        $workUnits   = WorkUnit::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'departments', 'workUnits'));
    }

    // ── Update — sys_admin ─────────────────────────────────────
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,'.$user->id,
            'role'          => 'required|in:'.implode(',', array_keys(User::ROLES)),
            'employee_id'   => 'nullable|string|max:50|unique:users,employee_id,'.$user->id,
            'phone'         => 'nullable|string|max:20',
            'position'      => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'work_unit_id'  => 'nullable|exists:work_units,id',
        ]);

        $oldRole           = $user->role;
        $data['is_active'] = $request->boolean('is_active', $user->is_active);
        $user->update($data);

        $log = $oldRole !== $user->role
            ? "Ubah role {$user->name}: {$oldRole} → {$user->role}"
            : "Update data: {$user->name}";
        AuditLogger::record('User', 'update', $log);

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    // ── Toggle aktif/nonaktif — sys_admin ─────────────────────
    public function toggleActive(User $user)
    {
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        AuditLogger::record('User', 'update', "Akun {$user->name} {$status}");
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    // ── Reset password — sys_admin ─────────────────────────────
    public function resetPassword(Request $request, User $user)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);
        $user->update(['password' => Hash::make($request->password)]);
        AuditLogger::record('User', 'update', "Reset password: {$user->name}");
        return back()->with('success', "Password {$user->name} berhasil direset.");
    }

    // ── Hapus — sys_admin —─────────────────────────────────────
    public function destroy(User $user)
    {
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $name = $user->name;
        $user->delete();
        AuditLogger::record('User', 'delete', "Hapus akun: {$name}");
        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$name} berhasil dihapus.");
    }
}