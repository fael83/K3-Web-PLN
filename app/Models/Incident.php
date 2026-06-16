<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';

    protected $fillable = [
        'incident_type',
        'title',
        'description',
        'location',
        'incident_date',
        'status',
        'corrective_action',
        'evidence_url',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public const TYPES = [
        'near_miss'          => 'Near Miss (Nyaris Celaka)',
        'kecelakaan_ringan'  => 'Kecelakaan Ringan',
        'kecelakaan_berat'   => 'Kecelakaan Berat',
        'kebakaran'          => 'Kebakaran',
        'lainnya'            => 'Lainnya',
    ];

    public const STATUSES = [
        'open'        => 'Open',
        'investigasi' => 'Investigasi',
        'selesai'     => 'Selesai',
    ];
}
