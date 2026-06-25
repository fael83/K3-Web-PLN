@extends('layouts.admin')

@section('title', $form->title)

@section('content')

<div class="row">

    <div class="col-md-4">

        <div class="card">
            <div class="card-header">
                Tambah Field
            </div>

            <div class="card-body">

                <form method="POST"
                    action="{{ route('admin.monitoring-forms.fields.store', ['monitoring_form' => $form->id]) }}">
                    @csrf

                    <div class="mb-3">
                        <label>Nama Field</label>

                        <input
                            type="text"
                            name="label"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Tipe Field</label>

                        <select
                            name="field_type"
                            class="form-control">

                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="yes_no">Yes / No</option>
                            <option value="checklist">Checklist</option>
                            <option value="dropdown">Dropdown</option>
                            <option value="date">Date</option>
                            <option value="photo">Photo</option>
                            <option value="signature">Signature</option>
                            <option value="rating">Rating</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>
                            Options
                            (Dropdown / Checklist)
                        </label>

                        <textarea
                            name="options"
                            class="form-control"
                            rows="3"></textarea>
                    </div>

                    <button class="btn btn-primary">
                        Tambah Field
                    </button>

                </form>

            </div>
        </div>

    </div>

    <div class="col-md-8">

        <div class="card">

            <div class="card-header">
                Daftar Field
            </div>

            <div class="card-body">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>Tipe</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($fields as $field)

                            <tr>
                                <td>{{ $field->label }}</td>
                                <td>{{ $field->field_type }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="2">
                                    Belum ada field
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection