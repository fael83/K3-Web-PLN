<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'revision_number',
        'file_url',
        'file_name',
        'file_type',
        'file_size',
        'status',
        'change_notes',
        'uploaded_by',
    ];

    // Relasi ke dokumen induk
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // Relasi ke user yang upload versi ini
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}