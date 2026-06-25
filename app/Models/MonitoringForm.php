<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringForm extends Model
{
    protected $table = 'monitoring_forms';

    protected $fillable = [
        'title',
        'description',
        'frequency',
        'assigned_role',
        'status',
    ];

    public const FREQUENCIES = [
        'daily' => 'Harian',
        'weekly' => 'Mingguan',
        'monthly' => 'Bulanan',
        'event' => 'Per Event',
        'adhoc' => 'Ad Hoc',
    ];

    public function fields()
    {
        return $this->hasMany(
            MonitoringFormField::class,
            'form_id'
        )->orderBy('sort_order');
    }

    public function submissions()
    {
        return $this->hasMany(
            MonitoringSubmission::class,
            'form_id'
        );
    }
}