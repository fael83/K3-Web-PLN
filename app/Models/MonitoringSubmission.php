<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringSubmission extends Model
{
    protected $table = 'monitoring_submissions';

    protected $fillable = [
        'form_id',
        'user_id',
        'responses',
        'status',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'responses'    => 'array',
        'submitted_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(
            MonitoringForm::class,
            'form_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function reviewer()
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function adminIndex()
{
    $submissions = MonitoringSubmission::with([
        'form',
        'user'
    ])
    ->latest()
    ->get();

    return view(
        'admin.monitoring_submissions.admin_index',
        compact('submissions')
    );
}

public function show(
    MonitoringSubmission $submission
)
{
    $submission->load([
        'form',
        'user'
    ]);

    return view(
        'admin.monitoring_submissions.show',
        compact('submission')
    );
}

public function approve(
    MonitoringSubmission $submission
)
{
    $submission->update([
        'status' => 'approved',
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    return back()
        ->with(
            'success',
            'Submission approved'
        );
}

public function reject(
    Request $request,
    MonitoringSubmission $submission
)
{
    $submission->update([
        'status' => 'rejected',
        'review_notes' => $request->review_notes,
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    return back()
        ->with(
            'success',
            'Submission rejected'
        );
}
}