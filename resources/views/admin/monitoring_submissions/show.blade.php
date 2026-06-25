@extends('layouts.admin')

@section('title','Submission Detail')

@section('content')

<div class="card">

    <div class="card-body">

        <h4>{{ $submission->form->title }}</h4>

        <p class="mb-1">
            <strong>User:</strong>
            {{ $submission->user->name }}
        </p>

        <p class="mb-3">
            <strong>Tanggal Submit:</strong>
            {{ $submission->submitted_at }}
        </p>

        <hr>

        <h5>Jawaban Form</h5>

        @foreach($submission->responses as $fieldId => $answer)

            <div class="mb-3">

                <strong>Field ID {{ $fieldId }}</strong>

                <br>

                @if(is_array($answer))
                    {{ implode(', ', $answer) }}
                @else
                    {{ $answer }}
                @endif

            </div>

        @endforeach

        <hr>

        <h5>Status Review</h5>

        <div class="mb-3">

            <span class="badge bg-{{
                $submission->status == 'approved'
                    ? 'success'
                    : ($submission->status == 'rejected'
                        ? 'danger'
                        : 'warning')
            }}">
                {{ strtoupper($submission->status) }}
            </span>

        </div>

        @if($submission->reviewed_at)

            <p>
                <strong>Reviewed At:</strong>
                {{ $submission->reviewed_at }}
            </p>

        @endif

        @if($submission->review_notes)

            <div class="alert alert-danger">

                <strong>Catatan Reviewer:</strong>

                <br>

                {{ $submission->review_notes }}

            </div>

        @endif

        @if($submission->status == 'submitted')

            <hr>

            <div class="d-flex gap-2">

                <form method="POST"
                      action="{{ route(
                        'admin.monitoring-submissions.approve',
                        $submission
                      ) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-success">

                        Approve

                    </button>

                </form>

                <button
                    class="btn btn-danger"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#rejectForm">

                    Reject

                </button>

            </div>

            <div class="collapse mt-3"
                 id="rejectForm">

                <form method="POST"
                      action="{{ route(
                        'admin.monitoring-submissions.reject',
                        $submission
                      ) }}">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">
                            Alasan Reject
                        </label>

                        <textarea
                            name="review_notes"
                            class="form-control"
                            rows="3"
                            required></textarea>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Konfirmasi Reject

                    </button>

                </form>

            </div>

        @endif

    </div>

</div>

@endsection