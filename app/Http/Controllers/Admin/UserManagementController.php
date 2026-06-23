<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Division;
use App\Models\User;
use App\Models\WorkUnit;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // ── Daftar User ───────────────────────────────────────────
    public function index(Request $request)
    {
        $query = User::with('department.division', 'workUnit')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('email', 'ilike', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'ilike', '%' . $request->search . '%');
            });
        }

        $users       = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'departments'));
    }

    // ── Form Buat User ─────────────────────────────────────────
    public function create()
    {
        $departments = Department::with('division')->orderBy('name')->get();
        $workUnits   = WorkUnit::orderBy('name')->get();
        return view('admin.users.form', [
            'user'        => new User(),
            'departments' => $departments,
            'workUnits'   => $workUnits,
        ]);
    }

    // ── Simpan User Baru ───────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'role'          => 'required|in:' . implode(',', array_keys(User::ROLES)),
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

        AuditLogger::record('User', 'create', "Membuat akun pengguna: {$user->name} ({$user->role})");

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil dibuat.");
    }

    // ── Detail User & Activity Log ─────────────────────────────
    public function show(User $user)
    {
        $user->load('department.division', 'workUnit');
        $activityLogs = AuditLog::where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(20);

        return view('admin.users.show', compact('user', 'activityLogs'));
    }

    // ── Form Edit User ─────────────────────────────────────────
    public function edit(User $user)
    {
        $departments = Department::with('division')->orderBy('name')->get();
        $workUnits   = WorkUnit::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'departments', 'workUnits'));
    }

    // ── Update User ────────────────────────────────────────────
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'role'          => 'required|in:' . implode(',', array_keys(User::ROLES)),
            'employee_id'   => 'nullable|string|max:50|unique:users,employee_id,' . $user->id,
            'phone'         => 'nullable|string|max:20',
            'position'      => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'work_unit_id'  => 'nullable|exists:work_units,id',
        ]);

        $oldRole = $user->role;
        $data['is_active'] = $request->boolean('is_active', $user->is_active);

        $user->update($data);

        if ($oldRole !== $user->role) {
            AuditLogger::record('User', 'update',
                "Ubah role {$user->name}: {$oldRole} → {$user->role}");
        } else {
            AuditLogger::record('User', 'update', "Memperbarui data pengguna: {$user->name}");
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    // ── Toggle Aktif/Nonaktif ──────────────────────────────────
    public function toggleActive(User $user)
    {
        // Jangan nonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        AuditLogger::record('User', 'update', "Akun {$user->name} {$status}");

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    // ── Reset Password ─────────────────────────────────────────
    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        AuditLogger::record('User', 'update', "Reset password untuk: {$user->name}");

        return back()->with('success', "Password {$user->name} berhasil direset.");
    }

    // ── Hapus User ─────────────────────────────────────────────
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $name = $user->name;
        $user->delete();

        AuditLogger::record('User', 'delete', "Menghapus akun pengguna: {$name}");

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$name} berhasil dihapus.");
    }
}
