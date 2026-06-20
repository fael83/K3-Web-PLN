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
        'description',
        'audit_type',
        'audit_date',
        'auditor_name',
        'status',
        'created_by',
    ];

    protected $casts = [
        'audit_date' => 'date',
    ];

    public const TYPES = [
        'internal'  => 'Audit Internal',
        'eksternal' => 'Audit Eksternal',
        'smk3'      => 'Audit SMK3',
        'iso45001'  => 'Audit ISO 45001',
    ];

    public const STATUSES = [
        'draft'       => 'Draft',
        'in_progress' => 'Sedang Berlangsung',
        'completed'   => 'Selesai',
    ];

    public function items()
    {
        return $this->hasMany(AuditItem::class, 'audit_id')->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getConformanceCountAttribute(): int
    {
        return $this->items->where('status', 'conformance')->count();
    }

    public function getMinorNcCountAttribute(): int
    {
        return $this->items->where('status', 'minor_nc')->count();
    }

    public function getMajorNcCountAttribute(): int
    {
        return $this->items->where('status', 'major_nc')->count();
    }

    public function getObservationCountAttribute(): int
    {
        return $this->items->where('status', 'observation')->count();
    }
}
