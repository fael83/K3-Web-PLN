<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['division_id', 'name', 'code', 'description'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function workUnits()
    {
        return $this->hasMany(WorkUnit::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
