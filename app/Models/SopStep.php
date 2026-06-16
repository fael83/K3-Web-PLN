<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SopStep extends Model
{
    use HasFactory;

    protected $table = 'sop_steps';

    protected $fillable = [
        'sector',
        'step_order',
        'title',
        'description',
    ];

    public const SECTORS = [
        'sebelum'      => 'Prosedur Sebelum Bekerja',
        'saat'         => 'Prosedur Saat Bekerja',
        'pembangkitan' => 'Prosedur Khusus Pembangkitan',
        'transmisi'    => 'Prosedur Khusus Transmisi (PDKB)',
        'setelah'      => 'Prosedur Setelah Bekerja',
    ];
}
