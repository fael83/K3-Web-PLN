<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProgram extends Model
{
    use HasFactory;

    protected $table = 'health_programs';

    protected $fillable = [
        'program_name',
        'description',
        'sort_order',
    ];
}
