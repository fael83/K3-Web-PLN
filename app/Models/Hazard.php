<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazard extends Model
{
    use HasFactory;

    protected $table = 'hazards';

    protected $fillable = [
        'category',
        'name',
        'description',
        'location',
        'likelihood',
        'severity',
        'control_measure',
    ];

    public const CATEGORIES = [
        'fisik_mekanik'    => 'Fisik dan Mekanik',
        'kimia'            => 'Kimia',
        'biomekanik'       => 'Biomekanik',
        'lingkungan'       => 'Lingkungan',
        'psikologi_sosial' => 'Psikologi dan Sosial',
    ];

    public const LEVELS = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
    ];
}
