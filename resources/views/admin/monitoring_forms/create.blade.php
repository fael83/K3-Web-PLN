@extends('layouts.admin')

@section('title','Buat Monitoring Form')

@section('content')

<div class="card">
    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.monitoring-forms.store') }}">

            @csrf

            <div class="mb-3">
                <label>Nama Form</label>
                <input
                    type="text"
                    name="title"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea
                    name="description"
                    class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Frekuensi</label>

                <select
                    name="frequency"
                    class="form-control">

                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="event">Per Event</option>

                </select>
            </div>

            <div class="mb-3">
                <label>Assigned Role</label>

                <select
                    name="assigned_role"
                    class="form-control">

                    <option value="employee">Employee</option>
                    <option value="department_head">
                        Department Head
                    </option>

                </select>
            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

        </form>

    </div>
</div>

@endsection