<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'team_members';

    protected $fillable = [
        'position',
        'name',
        'responsibility',
        'level',
        'sort_order',
    ];

    public const LEVELS = [
        'penanggung_jawab' => 'Penanggung Jawab',
        'ketua'            => 'Ketua',
        'sekretaris'       => 'Sekretaris',
        'koordinator'      => 'Koordinator',
        'anggota'          => 'Anggota',
    ];
}
