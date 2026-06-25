<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditChecklist extends Model
{
    use HasFactory;

    protected $table = 'audit_checklists';

    protected $fillable = [
        'title',
        'audit_code',
        'period_start',
        'period_end',
        'scope',
        'auditor_name',
        'status',
        'summary',
        'created_by',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public const TYPES = [
        'internal'  => 'Internal',
        'eksternal' => 'Eksternal',
        'smk3'      => 'SMK3',
        'iso45001'  => 'ISO 45001',
    ];

    public const STATUSES = [
        'draft'       => 'Draft',
        'in_progress' => 'Sedang Berlangsung',
        'completed'   => 'Selesai',
        'archived'    => 'Diarsipkan',
    ];

    public function items()
    {
        return $this->hasMany(AuditItem::class, 'audit_checklist_id')->orderBy('sort_order');
    }

    public function evidencePackages()
    {
        return $this->hasMany(AuditEvidencePackage::class, 'audit_checklist_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCompliantCountAttribute(): int
    {
        return $this->items->where('compliance_status', 'compliant')->count();
    }

    public function getObservationCountAttribute(): int
    {
        return $this->items->where('compliance_status', 'observation')->count();
    }

    public function getMinorCountAttribute(): int
    {
        return $this->items->where('compliance_status', 'minor')->count();
    }

    public function getMajorCountAttribute(): int
    {
        return $this->items->where('compliance_status', 'major')->count();
    }
}