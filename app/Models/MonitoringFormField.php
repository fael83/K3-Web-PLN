<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringFormField extends Model
{
    protected $table = 'monitoring_form_fields';

    public $timestamps = false;

    protected $fillable = [
        'form_id',
        'label',
        'field_type',
        'options',
        'sort_order',
    ];

    public const TYPES = [
        'text'      => 'Text',
        'number'    => 'Number',
        'yes_no'    => 'Yes / No',
        'checklist' => 'Checklist',
        'dropdown'  => 'Dropdown',
        'date'      => 'Date',
        'photo'     => 'Photo',
        'rating'    => 'Rating',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(
            MonitoringForm::class,
            'form_id'
        );
    }
}