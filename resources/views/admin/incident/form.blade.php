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

                {{-- ── Baris 1: Jenis + Status ── --}}
                <div class="col-md-6">
                    <label class="form-label">Jenis Insiden <span class="text-danger">*</span></label>
                    <select name="incident_type" class="form-select" required>
                        <option value="">— Pilih jenis —</option>
                        @foreach (\App\Models\Incident::TYPES as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('incident_type', $item->incident_type) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach (\App\Models\Incident::STATUSES as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('status', $item->status ?? 'open') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ── Baris 2: Judul + Tanggal ── --}}
                <div class="col-md-8">
                    <label class="form-label">Judul Insiden <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required
                           value="{{ old('title', $item->title) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Kejadian</label>
                    <input type="date" name="incident_date" class="form-control"
                           value="{{ old('incident_date', optional($item->incident_date)->format('Y-m-d')) }}">
                </div>

                {{-- ── Baris 3: Departemen (hanya admin/manager/officer yang bisa pilih) ── --}}
                @php $authRole = auth()->user()->role; @endphp

                @if(in_array($authRole, ['sys_admin', 'k3_manager', 'k3_officer']))
                <div class="col-md-6">
                    <label class="form-label">Departemen Terkait</label>
                    <select name="department_id" class="form-select">
                        <option value="">— Pilih departemen (opsional) —</option>
                        @foreach(\App\Models\Department::orderBy('name')->get() as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('department_id', $item->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Kosongkan jika insiden lintas departemen.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-control"
                           value="{{ old('location', $item->location) }}"
                           placeholder="Contoh: Gedung A, Lantai 2">
                </div>
                @else
                {{-- Department head & employee: departemen otomatis dari profil user, hanya tampil lokasi --}}
                <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                <div class="col-12">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-control"
                           value="{{ old('location', $item->location) }}"
                           placeholder="Contoh: Gedung A, Lantai 2">
                </div>
                @endif

                {{-- ── Baris 4: Deskripsi ── --}}
                <div class="col-12">
                    <label class="form-label">Deskripsi Kejadian</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Jelaskan kronologi dan detail kejadian...">{{ old('description', $item->description) }}</textarea>
                </div>

                {{-- ── Baris 5: Tindakan Korektif ── --}}
                <div class="col-12">
                    <label class="form-label">Tindakan Korektif (CAPA)</label>
                    <textarea name="corrective_action" class="form-control" rows="3"
                              placeholder="Langkah-langkah penanganan dan pencegahan berulang...">{{ old('corrective_action', $item->corrective_action) }}</textarea>
                    @if(!$item->corrective_action && $item->exists)
                        <div class="form-text text-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            CAPA belum diisi — insiden ini dihitung sebagai CAPA terbuka di dashboard.
                        </div>
                    @endif
                </div>

                {{-- ── Baris 6: Evidence ── --}}
                <div class="col-12">
                    <label class="form-label">Bukti / Evidence (Gambar)</label>
                    @if ($item->evidence_url)
                        <div class="mb-2 d-flex align-items-center gap-3">
                            <img src="{{ $item->evidence_url }}" alt="Evidence"
                                 class="rounded border" style="height:90px;object-fit:cover;">
                            <span class="text-muted small">
                                <i class="bi bi-image me-1"></i>
                                Gambar saat ini. Upload baru untuk mengganti.
                            </span>
                        </div>
                    @endif
                    <input type="file" name="evidence" class="form-control" accept="image/*">
                    <div class="form-text">Format JPG/PNG/WEBP, maksimal 4 MB.</div>
                </div>

            </div>{{-- end .row --}}

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-pln">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.incident.index') }}" class="btn btn-light">Batal</a>
            </div>

        </form>
    </div>
</div>
@endsection