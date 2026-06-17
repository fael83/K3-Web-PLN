@extends('layouts.admin')
@section('title', isset($item) ? 'Edit Anggota Tim' : 'Tambah Anggota Tim')

@section('content')
@include('admin.partials._header', [
    'heading'    => isset($item) ? 'Edit Anggota Tim K3' : 'Tambah Anggota Tim K3',
    'subheading' => 'Lengkapi data jabatan, nama, dan foto anggota tim K3.',
])

@include('admin.partials._errors')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST"
              action="{{ isset($item) ? route('admin.team.update', $item->id) : route('admin.team.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($item)) @method('PUT') @endif

            <div class="row g-3">

                {{-- NAMA --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           placeholder="cth. Budi Santoso"
                           value="{{ old('nama', $item->nama ?? '') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- URUTAN --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Urutan Tampil <span class="text-danger">*</span></label>
                    <input type="number" name="sort_order"
                           class="form-control @error('sort_order') is-invalid @enderror"
                           min="0" placeholder="1"
                           value="{{ old('sort_order', $item->sort_order ?? 1) }}" required>
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- JABATAN --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                    <select name="jabatan"
                            class="form-select @error('jabatan') is-invalid @enderror" required>
                        <option value="" disabled {{ old('jabatan', $item->jabatan ?? '') === '' ? 'selected' : '' }}>
                            — Pilih Jabatan —
                        </option>
                        @php
                        $jabatanList = [
                            'Penanggung Jawab K3',
                            'Ketua P2K3',
                            'Sekretaris P2K3',
                            'Koordinator Identifikasi Bahaya',
                            'Koordinator Tanggap Darurat',
                            'Koordinator APD dan Logistik',
                            'Koordinator Kesehatan Kerja',
                            'Koordinator Pelatihan K3',
                            'Anggota Perwakilan Unit',
                        ];
                        @endphp
                        @foreach($jabatanList as $jab)
                            <option value="{{ $jab }}"
                                {{ old('jabatan', $item->jabatan ?? '') === $jab ? 'selected' : '' }}>
                                {{ $jab }}
                            </option>
                        @endforeach
                    </select>
                    @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- TANGGUNG JAWAB --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Tanggung Jawab</label>
                    <textarea name="responsibility"
                              class="form-control @error('responsibility') is-invalid @enderror"
                              rows="3"
                              placeholder="Deskripsi singkat tanggung jawab...">{{ old('responsibility', $item->responsibility ?? '') }}</textarea>
                    @error('responsibility')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- FOTO --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Foto</label>

                    {{-- Preview foto saat edit --}}
                    @if(isset($item) && !empty($item->foto))
                    <div class="mb-2">
                        <img src="{{ $item->foto }}" alt="Foto {{ $item->nama }}"
                             class="rounded-circle border"
                             style="width:90px;height:90px;object-fit:cover;">
                        <p class="text-muted small mt-1 mb-0">Foto saat ini. Upload baru untuk mengganti.</p>
                    </div>
                    @endif

                    <input type="file" name="foto" id="foto"
                           class="form-control @error('foto') is-invalid @enderror"
                           accept="image/jpg,image/jpeg,image/png,image/webp"
                           onchange="previewFoto(this)">
                    <div class="form-text">Format: JPG, PNG, WEBP. Maks 2 MB.</div>
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    {{-- Preview sebelum upload --}}
                    <div id="foto-preview-wrap" class="mt-2 d-none">
                        <img id="foto-preview" src="#" alt="Preview"
                             class="rounded-circle border"
                             style="width:90px;height:90px;object-fit:cover;">
                        <p class="text-muted small mt-1 mb-0">Preview foto baru.</p>
                    </div>
                </div>

            </div>{{-- row --}}

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-pln">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.team.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewFoto(input) {
    const wrap = document.getElementById('foto-preview-wrap');
    const img  = document.getElementById('foto-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; wrap.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    } else {
        wrap.classList.add('d-none');
    }
}
</script>
@endsection