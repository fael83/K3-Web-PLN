@extends('layouts.admin')

@section('title','Monitoring Forms')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Monitoring Forms</h4>

    <a href="{{ route('admin.monitoring-forms.create') }}"
       class="btn btn-primary">
        Tambah Form
    </a>
</div>

<div class="card">
    <div class="card-body">

        <table class="table">
            <thead>
                <tr>
                    <th>Nama Form</th>
                    <th>Frequency</th>
                    <th>Assigned Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($forms as $form)

                    <tr>
                        <td>{{ $form->title }}</td>
                        <td>{{ $form->frequency }}</td>
                        <td>{{ $form->assigned_role }}</td>

                        <td>
                            <a href="{{ route('admin.monitoring-forms.show', $form) }}"
                            class="btn btn-sm btn-primary">
                                Builder
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3">
                            Belum ada form monitoring
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
</div>

@endsection