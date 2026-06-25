<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditItem extends Model
{
    use HasFactory;

    protected $table = 'audit_items';

    protected $fillable = [
        'audit_checklist_id',   // ← fix: sesuai kolom di DB setelah migration
        'item_name',
        'description',
        'status',
        'finding',
        'corrective_action',
        'evidence_ref',
        'sort_order',
    ];

    public const STATUSES = [
        'pending'     => 'Belum Dinilai',
        'conformance' => 'Conformance',
        'minor_nc'    => 'Minor Non-Conformance',
        'major_nc'    => 'Major Non-Conformance',
        'observation' => 'Observation',
    ];

    public const STATUS_CLASSES = [
        'pending'     => 'secondary',
        'conformance' => 'success',
        'minor_nc'    => 'warning',
        'major_nc'    => 'danger',
        'observation' => 'info',
    ];

    public function audit()
    {
        return $this->belongsTo(AuditChecklist::class, 'audit_checklist_id');
    }
}