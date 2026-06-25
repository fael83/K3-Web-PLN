@extends('layouts.admin')

@section('title',$form->title)

@section('content')

<form method="POST"
action="{{ route(
'admin.monitoring-submissions.store',
$form
) }}">

@csrf

@foreach($fields as $field)

<div class="mb-3">

<label class="form-label">
{{ $field->label }}
</label>

@if($field->field_type == 'text')

<input
type="text"
name="responses[{{ $field->id }}]"
class="form-control">

@endif

@if($field->field_type == 'number')

<input
type="number"
name="responses[{{ $field->id }}]"
class="form-control">

@endif

@if($field->field_type == 'yes_no')

<select
name="responses[{{ $field->id }}]"
class="form-control">

<option value="Yes">Yes</option>
<option value="No">No</option>

</select>

@endif

@if($field->field_type == 'rating')

<select
name="responses[{{ $field->id }}]"
class="form-control">

@for($i=1;$i<=5;$i++)

<option value="{{ $i }}">
{{ $i }}
</option>

@endfor

</select>

@endif

</div>

@endforeach

<button class="btn btn-success">
Submit Form
</button>

</form>

@endsection