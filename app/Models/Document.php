<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderByDesc('revision_number');
    }

    public function scopeExpiringSoon($query)
    {
        return $query->whereNotNull('review_date')
            ->whereDate('review_date', '<=', now()->addDays(30))
            ->whereDate('review_date', '>=', now())
            ->where('status', 'approved');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'ilike', "%{$term}%")
                ->orWhere('document_number', 'ilike', "%{$term}%")
                ->orWhere('description', 'ilike', "%{$term}%")
                ->orWhere('owner_department', 'ilike', "%{$term}%");
        });
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'under_review';
    }
}