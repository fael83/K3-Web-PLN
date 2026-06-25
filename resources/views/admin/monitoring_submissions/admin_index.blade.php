@extends('layouts.admin')

@section('title','Monitoring Submissions')

@section('content')

<div class="card">
<div class="card-body">

<table class="table">

<thead>
<tr>
<th>ID</th>
<th>Form</th>
<th>User</th>
<th>Status</th>
<th>Tanggal</th>
<th></th>
</tr>
</thead>

<tbody>

@foreach($submissions as $submission)

<tr>

<td>{{ $submission->id }}</td>

<td>{{ $submission->form->title }}</td>

<td>{{ $submission->user->name }}</td>

<td>{{ $submission->status }}</td>

<td>{{ $submission->submitted_at }}</td>

<td>

<a
href="{{ route(
'admin.monitoring-submissions.show',
$submission
) }}"
class="btn btn-sm btn-primary">

Detail

</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>

@endsection