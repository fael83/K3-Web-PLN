<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonitoringForm;
use App\Models\MonitoringSubmission;
use Illuminate\Http\Request;

class MonitoringSubmissionController extends Controller
{
   public function index()
{
    $userId = auth()->id();

    $forms = MonitoringForm::where(
        'assigned_role',
        auth()->user()->role
    )->get();

    $submittedFormIds = MonitoringSubmission::where(
        'user_id',
        $userId
    )->pluck('form_id')
     ->toArray();

    return view(
        'admin.monitoring_submissions.index',
        compact(
            'forms',
            'submittedFormIds'
        )
    );
}

    public function create(
        MonitoringForm $form
    )
    {
        $fields = $form->fields;

        return view(
            'admin.monitoring_submissions.create',
            compact('form', 'fields')
        );
    }

public function store(
    Request $request,
    MonitoringForm $form
)
{
    $request->validate([
        'responses' => 'required|array|min:1'
    ]);

    MonitoringSubmission::create([
        'form_id'      => $form->id,
        'user_id'      => auth()->id(),
        'responses'    => $request->responses,
        'status'       => 'submitted',
        'submitted_at' => now(),
    ]);

    return redirect()
        ->route(
            'admin.monitoring-submissions.index'
        )
        ->with(
            'success',
            'Form berhasil dikirim'
        );
}

public function adminIndex()
{
    $submissions = MonitoringSubmission::with([
        'user',
        'form'
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

    return redirect()->route(
        'admin.monitoring-submissions.show',
        $submission
    );
}

public function reject(
    Request $request,
    MonitoringSubmission $submission
)
{
    $submission->update([
        'status' => 'rejected',
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
        'review_notes' => $request->review_notes,
    ]);

    return redirect()->route(
        'admin.monitoring-submissions.show',
        $submission
    );
}
}