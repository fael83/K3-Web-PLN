<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'document_number',
        'category',
        'owner_department',
        'status',
        'file_url',
        'file_name',
        'file_type',
        'file_size',
        'revision_number',
        'effective_date',
        'review_date',
        'description',
        'uploaded_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'review_date'    => 'date',
        'approved_at'    => 'datetime',
    ];

    // Relasi ke user yang upload
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Relasi ke user yang approve
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi ke semua versi dokumen
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderByDesc('revision_number');
    }

    // Scope: dokumen yang hampir expired (review_date < 30 hari lagi)
    public function scopeExpiringSoon($query)
    {
        return $query->whereNotNull('review_date')
                     ->whereDate('review_date', '<=', now()->addDays(30))
                     ->whereDate('review_date', '>=', now())
                     ->where('status', 'approved');
    }

    // Scope: filter by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helper: cek apakah dokumen bisa di-approve
    public function canBeApproved(): bool
    {
        return $this->status === 'under_review';
    }
}