<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MonitoringSubmission;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'department_id', 'work_unit_id',
        'employee_id', 'phone', 'position',
        'is_active', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public const ROLES = [
        'sys_admin'       => 'System Admin',
        'k3_manager'      => 'K3 Manager',
        'k3_officer'      => 'K3 Officer',
        'department_head' => 'Department Head',
        'employee'        => 'Employee',
        'auditor'         => 'Auditor',
        'viewer'          => 'Viewer',
    ];

    // ── Relations ─────────────────────────────────────────────
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // ── Helpers ───────────────────────────────────────────────
    public function hasRole(...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function monitoringSubmissions()
    {
        return $this->hasMany(
            MonitoringSubmission::class,
            'user_id'
        );
    }
}
