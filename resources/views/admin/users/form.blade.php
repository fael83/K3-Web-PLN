@extends('layouts.admin')
@section('title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
@include('admin.partials._header', [
    'heading'    => $user->exists ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna Baru',
    'subheading' => 'Isi data pengguna, peran, dan penugasan departemen.',
])

<form method="POST"
      action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
    @csrf
    @if($user->exists) @method('PUT') @endif

    <div class="row g-3">
        {{-- Kolom kiri: Data Pengguna --}}
        <div class="col-md-8">
            {{-- Info Dasar --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent fw-semibold">
                    <i class="bi bi-person me-2 text-primary"></i>Informasi Dasar
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" placeholder="Nama lengkap">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" placeholder="email@pln.co.id">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP / ID Karyawan</label>
                            <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror"
                                   value="{{ old('employee_id', $user->employee_id) }}" placeholder="Nomor induk pegawai">
                            @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Jabatan / Posisi</label>
                            <input type="text" name="position" class="form-control"
                                   value="{{ old('position', $user->position) }}"
                                   placeholder="cth: Senior K3 Officer, Supervisor Lapangan">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penugasan Organisasi --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent fw-semibold">
                    <i class="bi bi-diagram-2 me-2 text-primary"></i>Penugasan Organisasi
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Departemen</label>
                            <select name="department_id" class="form-select" id="deptSelect">
                                <option value="">— Pilih Departemen —</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        data-division="{{ $dept->division->name ?? '' }}"
                                        {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                        @if($dept->division) ({{ $dept->division->name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Unit Kerja</label>
                            <select name="work_unit_id" class="form-select">
                                <option value="">— Pilih Unit Kerja —</option>
                                @foreach($workUnits as $wu)
                                    <option value="{{ $wu->id }}"
                                        {{ old('work_unit_id', $user->work_unit_id) == $wu->id ? 'selected' : '' }}>
                                        {{ $wu->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">
                    <i class="bi bi-key me-2 text-primary"></i>
                    Password {{ $user->exists ? '(kosongkan jika tidak ingin diubah)' : '' }}
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Password {{ !$user->exists ? '<span class="text-danger">*</span>' : '' }}
                            </label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter"
                                   {{ !$user->exists ? 'required' : '' }}>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom kanan: Role & Status --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent fw-semibold">
                    <i class="bi bi-shield-lock me-2 text-primary"></i>Role & Akses
                </div>
                <div class="card-body">
                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror">
                        @foreach(\App\Models\User::ROLES as $val => $label)
                            <option value="{{ $val }}" {{ old('role', $user->role) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <hr>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active"
                               id="isActive" value="1"
                               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Akun Aktif</label>
                    </div>
                    <div class="form-text">Akun nonaktif tidak bisa login ke sistem.</div>
                </div>
            </div>

            {{-- Permission Info --}}
            <div class="card border-0 shadow-sm mb-3 bg-light-subtle">
                <div class="card-body small text-muted">
                    <div class="fw-semibold text-dark mb-2">Panduan Role:</div>
                    <ul class="mb-0 ps-3">
                        <li><strong>Sys Admin</strong> — akses penuh</li>
                        <li><strong>K3 Manager</strong> — kelola semua data K3</li>
                        <li><strong>K3 Officer</strong> — input & laporan K3</li>
                        <li><strong>Dept. Head</strong> — lihat data departemennya</li>
                        <li><strong>Employee</strong> — input insiden & form</li>
                        <li><strong>Auditor</strong> — baca + audit trail</li>
                        <li><strong>Viewer</strong> — baca saja</li>
                    </ul>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    {{ $user->exists ? 'Simpan Perubahan' : 'Buat Pengguna' }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection
