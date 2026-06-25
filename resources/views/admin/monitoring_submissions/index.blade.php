@extends('layouts.admin')

@section('title','Monitoring Forms')

@section('content')

<div class="card">

<div class="card-body">

<table class="table">

<thead>
<tr>
<th>Form</th>
<th>Frequency</th>
<th></th>
</tr>
</thead>

<tbody>

@foreach($forms as $form)

<tr>

<td>{{ $form->title }}</td>

<td>{{ $form->frequency }}</td>

<td>

@if(in_array($form->id,$submittedFormIds))

<span class="badge bg-success">
Sudah Diisi
</span>

@else

<a href="{{ route(
'admin.monitoring-submissions.create',
$form
) }}"
class="btn btn-primary btn-sm">

Isi Form

</a>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection