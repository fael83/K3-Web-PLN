@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Insiden' : 'Catat Insiden')

@section('content')
@include('admin.partials._header', [
    'heading'    => $item->exists ? 'Edit Insiden' : 'Catat Insiden',
    'subheading' => 'Dokumentasikan kejadian beserta tindak lanjut korektif.',
])

@include('admin.partials._errors')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST"
              action="{{ $item->exists ? route('admin.incident.update', $item) : route('admin.incident.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jenis Insiden <span class="text-danger">*</span></label>
                    <select name="incident_type" class="form-select" required>
                        <option value="">— Pilih jenis —</option>
                        @foreach (\App\Models\Incident::TYPES as $key => $label)
                            <option value="{{ $key }}" {{ old('incident_type', $item->incident_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach (\App\Models\Incident::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $item->status ?? 'open') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Judul Insiden <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title', $item->title) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Kejadian</label>
                    <input type="date" name="incident_date" class="form-control"
                           value="{{ old('incident_date', optional($item->incident_date)->format('Y-m-d')) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $item->location) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi Kejadian</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Tindakan Korektif</label>
                    <textarea name="corrective_action" class="form-control" rows="3">{{ old('corrective_action', $item->corrective_action) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Bukti / Evidence (Gambar)</label>
                    @if ($item->evidence_url)
                        <div class="mb-2">
                            <img src="{{ $item->evidence_url }}" alt="" class="rounded border" style="height:90px;object-fit:cover;">
                        </div>
                    @endif
                    <input type="file" name="evidence" class="form-control" accept="image/*">
                    <div class="form-text">Format gambar maks. 4 MB. Kosongkan bila tidak ingin mengubah.</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-pln"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.incident.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
