@extends('layouts.admin')
@section('title', 'Struktur Organisasi')

@section('content')
@include('admin.partials._header', [
    'heading'    => 'Struktur Organisasi',
    'subheading' => 'Kelola hierarki Divisi → Departemen → Unit Kerja.',
])

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    {{-- DIVISI --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-building me-2 text-primary"></i>Divisi</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addDivForm">
                    <i class="bi bi-plus"></i>
                </button>
            </div>

            {{-- Form tambah divisi --}}
            <div class="collapse p-3 border-bottom bg-light-subtle" id="addDivForm">
                <form method="POST" action="{{ route('admin.organization.division.store') }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm"
                               placeholder="Nama divisi" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="code" class="form-control form-control-sm"
                               placeholder="Kode (opsional)">
                    </div>
                    <button class="btn btn-primary btn-sm w-100">Tambah Divisi</button>
                </form>
            </div>

            <div class="card-body p-0">
                @forelse($divisions as $div)
                <div class="border-bottom p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">{{ $div->name }}</div>
                            @if($div->code)
                                <span class="badge bg-light text-dark border small">{{ $div->code }}</span>
                            @endif
                            <div class="text-muted small mt-1">
                                {{ $div->departments->count() }} departemen ·
                                {{ $div->departments->sum(fn($d) => $d->users->count()) }} anggota
                            </div>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-outline-secondary btn-sm"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#editDiv{{ $div->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST"
                                  action="{{ route('admin.organization.division.destroy', $div) }}"
                                  onsubmit="return confirm('Hapus divisi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    {{-- Edit form --}}
                    <div class="collapse mt-2" id="editDiv{{ $div->id }}">
                        <form method="POST" action="{{ route('admin.organization.division.update', $div) }}"
                              class="border rounded p-2 bg-light-subtle">
                            @csrf @method('PUT')
                            <div class="mb-1">
                                <input type="text" name="name" class="form-control form-control-sm"
                                       value="{{ $div->name }}" required>
                            </div>
                            <div class="mb-1">
                                <input type="text" name="code" class="form-control form-control-sm"
                                       value="{{ $div->code }}" placeholder="Kode">
                            </div>
                            <button class="btn btn-warning btn-sm w-100">Simpan</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted small">Belum ada divisi.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- DEPARTEMEN --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-diagram-2 me-2 text-success"></i>Departemen</span>
                <button class="btn btn-success btn-sm" data-bs-toggle="collapse" data-bs-target="#addDeptForm">
                    <i class="bi bi-plus"></i>
                </button>
            </div>

            <div class="collapse p-3 border-bottom bg-light-subtle" id="addDeptForm">
                <form method="POST" action="{{ route('admin.organization.department.store') }}">
                    @csrf
                    <div class="mb-2">
                        <select name="division_id" class="form-select form-select-sm">
                            <option value="">— Pilih Divisi —</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}">{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm"
                               placeholder="Nama departemen" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="code" class="form-control form-control-sm"
                               placeholder="Kode (opsional)">
                    </div>
                    <button class="btn btn-success btn-sm w-100">Tambah Departemen</button>
                </form>
            </div>

            <div class="card-body p-0" style="max-height:600px;overflow-y:auto;">
                @foreach($divisions as $div)
                    @foreach($div->departments as $dept)
                    <div class="border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold small">{{ $dept->name }}</div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ $div->name }} ·
                                    {{ $dept->users->count() }} anggota ·
                                    {{ $dept->workUnits->count() }} unit
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <button class="btn btn-outline-secondary btn-sm"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#editDept{{ $dept->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST"
                                      action="{{ route('admin.organization.department.destroy', $dept) }}"
                                      onsubmit="return confirm('Hapus departemen ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="collapse mt-2" id="editDept{{ $dept->id }}">
                            <form method="POST"
                                  action="{{ route('admin.organization.department.update', $dept) }}"
                                  class="border rounded p-2 bg-light-subtle">
                                @csrf @method('PUT')
                                <select name="division_id" class="form-select form-select-sm mb-1">
                                    <option value="">— Pilih Divisi —</option>
                                    @foreach($divisions as $d)
                                        <option value="{{ $d->id }}" {{ $dept->division_id == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="name" class="form-control form-control-sm mb-1"
                                       value="{{ $dept->name }}" required>
                                <input type="text" name="code" class="form-control form-control-sm mb-1"
                                       value="{{ $dept->code }}" placeholder="Kode">
                                <button class="btn btn-warning btn-sm w-100">Simpan</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    {{-- UNIT KERJA --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2 text-warning"></i>Unit Kerja</span>
                <button class="btn btn-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#addWuForm">
                    <i class="bi bi-plus"></i>
                </button>
            </div>

            <div class="collapse p-3 border-bottom bg-light-subtle" id="addWuForm">
                <form method="POST" action="{{ route('admin.organization.work-unit.store') }}">
                    @csrf
                    <div class="mb-2">
                        <select name="department_id" class="form-select form-select-sm" required>
                            <option value="">— Pilih Departemen —</option>
                            @foreach($divisions as $div)
                                <optgroup label="{{ $div->name }}">
                                    @foreach($div->departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm"
                               placeholder="Nama unit kerja" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="code" class="form-control form-control-sm"
                               placeholder="Kode (opsional)">
                    </div>
                    <button class="btn btn-warning btn-sm w-100">Tambah Unit Kerja</button>
                </form>
            </div>

            <div class="card-body p-0" style="max-height:600px;overflow-y:auto;">
                @foreach($divisions as $div)
                    @foreach($div->departments as $dept)
                        @foreach($dept->workUnits as $wu)
                        <div class="border-bottom p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold small">{{ $wu->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $dept->name }} · {{ $wu->users->count() ?? 0 }} anggota
                                    </div>
                                </div>
                                <form method="POST"
                                      action="{{ route('admin.organization.work-unit.destroy', $wu) }}"
                                      onsubmit="return confirm('Hapus unit kerja ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
