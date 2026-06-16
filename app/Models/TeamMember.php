<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'k3_team';

    protected $fillable = [
        'nama',
        'jabatan',
        'responsibility',
        'sort_order',
        'foto',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}