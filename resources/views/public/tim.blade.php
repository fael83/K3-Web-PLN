@extends('layouts.public')
@section('title', 'Struktur Tim K3')

@section('content')

@include('public._pagehead', [
    'eyebrow'  => 'Organisasi P2K3',
    'title'    => 'Struktur Tim K3',
    'subtitle' => 'Susunan organisasi Panitia Pembina Keselamatan dan Kesehatan Kerja (P2K3) PT PLN (Persero).',
])

<style>
/* ─── TREE WRAPPER ─── */
.org-wrap { overflow-x: auto; padding: 2rem 1rem 3.5rem; }
.org-tree { display: flex; flex-direction: column; align-items: center; min-width: 620px; }

/* ─── CONNECTOR LINES ─── */
.v-line {
    width: 2px; height: 44px; margin: 0 auto;
    background: linear-gradient(to bottom, var(--pln-blue), var(--pln-sky));
}
.h-row {
    display: flex; justify-content: center; align-items: flex-start;
    position: relative; width: 100%;
}
/* vertical tick per child */
.h-row > .col-node {
    display: flex; flex-direction: column; align-items: center; position: relative;
}
.h-row > .col-node::before {
    content: ''; display: block;
    width: 2px; height: 36px;
    background: linear-gradient(to bottom, var(--pln-sky), var(--pln-blue));
}
/* horizontal spanning bar */
.h-row.has-bar::after {
    content: '';
    position: absolute; top: 0;
    left: var(--hl, 12%); right: var(--hr, 12%);
    height: 2px;
    background: linear-gradient(to right, var(--pln-blue), var(--pln-sky), var(--pln-blue));
}

/* ─── BASE NODE ─── */
.org-node {
    border-radius: 16px; text-align: center;
    transition: transform .18s, box-shadow .18s;
    position: relative; z-index: 1;
}
.org-node:hover { transform: translateY(-5px); }

/* Level: Penanggung Jawab */
.node-pj {
    background: linear-gradient(135deg, var(--pln-blue-dark) 0%, #1a5bbf 100%);
    border: 2px solid var(--pln-yellow);
    box-shadow: 0 10px 36px rgba(14,47,102,.32);
    color: #fff; padding: 1.4rem 1.6rem; min-width: 235px;
}
.node-pj:hover { box-shadow: 0 18px 44px rgba(14,47,102,.38); }

/* Level: Ketua */
.node-ketua {
    background: linear-gradient(135deg, var(--pln-blue) 0%, var(--pln-sky) 100%);
    border: 2px solid rgba(255,255,255,.25);
    box-shadow: 0 6px 22px rgba(14,47,102,.22);
    color: #fff; padding: 1.1rem 1.3rem; min-width: 200px;
}
.node-ketua:hover { box-shadow: 0 12px 32px rgba(14,47,102,.3); }

/* Level: Sekretaris */
.node-sekretaris {
    background: #fff;
    border: 2px solid var(--pln-sky);
    box-shadow: 0 4px 16px rgba(0,158,227,.14);
    color: var(--ink); padding: 1.1rem 1.3rem; min-width: 200px;
}
.node-sekretaris:hover { box-shadow: 0 10px 28px rgba(0,158,227,.22); }

/* Level: Koordinator */
.node-koordinator {
    background: #fff;
    border: 1.5px solid var(--line);
    box-shadow: 0 3px 10px rgba(14,47,102,.07);
    color: var(--ink); padding: .9rem 1rem;
    min-width: 148px; max-width: 172px;
}
.node-koordinator:hover { box-shadow: 0 8px 22px rgba(14,47,102,.14); }

/* Level: Anggota */
.node-anggota {
    background: var(--surface);
    border: 1.5px dashed #c8d0de;
    color: var(--ink); padding: .8rem .95rem;
    min-width: 145px; max-width: 168px;
}
.node-anggota:hover { box-shadow: 0 6px 18px rgba(14,47,102,.1); }

/* ─── AVATAR / FOTO ─── */
.org-av {
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 50%; margin-bottom: .6rem; overflow: hidden;
    flex-shrink: 0;
}
.org-av img { width: 100%; height: 100%; object-fit: cover; }

/* avatar sizes per level */
.node-pj .org-av          { width:68px;height:68px; border:3px solid rgba(255,194,14,.6); }
.node-ketua .org-av        { width:58px;height:58px; border:2px solid rgba(255,255,255,.4); }
.node-sekretaris .org-av   { width:56px;height:56px; border:2px solid rgba(0,158,227,.35); }
.node-koordinator .org-av  { width:46px;height:46px; border:1.5px solid rgba(20,72,154,.2); }
.node-anggota .org-av      { width:42px;height:42px; border:1.5px solid #c8d0de; }

/* fallback icon inside avatar */
.org-av-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 100%; height: 100%; border-radius: 50%;
}
.node-pj .org-av-icon          { background:rgba(255,194,14,.18); color:var(--pln-yellow); font-size:1.6rem; }
.node-ketua .org-av-icon        { background:rgba(255,255,255,.18); color:#fff; font-size:1.4rem; }
.node-sekretaris .org-av-icon   { background:rgba(0,158,227,.1); color:var(--pln-sky); font-size:1.35rem; }
.node-koordinator .org-av-icon  { background:rgba(20,72,154,.08); color:var(--pln-blue); font-size:1.15rem; }
.node-anggota .org-av-icon      { background:rgba(91,101,119,.09); color:var(--muted); font-size:1.05rem; }

/* ─── TEXT IN NODE ─── */
.nd-jabatan { font-family:var(--font-display); font-weight:700; line-height:1.3; margin-bottom:.28rem; }
.node-pj .nd-jabatan         { font-size:.9rem; }
.node-ketua .nd-jabatan      { font-size:.85rem; }
.node-sekretaris .nd-jabatan { font-size:.85rem; color:var(--pln-blue-dark); }
.node-koordinator .nd-jabatan{ font-size:.78rem; }
.node-anggota .nd-jabatan    { font-size:.76rem; }

.nd-nama { font-size:.73rem; font-weight:600; opacity:.85; margin-bottom:.22rem; }
.nd-resp { font-size:.67rem; opacity:.62; line-height:1.35; }

/* crown icon */
.crown-icon { color:var(--pln-yellow); font-size:.85rem; margin-bottom:.15rem; display:block; }

/* ─── LEGEND ─── */
.org-legend { display:flex; flex-wrap:wrap; gap:.75rem; justify-content:center; }
.leg-item   { display:flex; align-items:center; gap:.45rem; font-size:.8rem; color:var(--muted); }
.leg-dot    { width:13px;height:13px;border-radius:4px;flex-shrink:0; }

/* ─── MOBILE ─── */
@media(max-width:767px){
    .org-tree { min-width: 340px; }
    .h-row { flex-direction: column; align-items: center; }
    .h-row.has-bar::after { display: none; }
    .h-row > .col-node::before { height: 20px; }
    .org-node { min-width: 220px !important; max-width: 260px !important; }
}
</style>

<section class="section section-alt">
<div class="container">

@if($team->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-people d-block mb-3" style="font-size:3rem;opacity:.3;"></i>
        <p class="fw-semibold mb-1">Belum ada data anggota tim K3.</p>
        <p class="small">Silakan tambahkan melalui panel admin.</p>
    </div>
@else

@php
    $pj    = $team->filter(fn($m)=>stripos($m->jabatan,'penanggung jawab')!==false);
    $ketua = $team->filter(fn($m)=>stripos($m->jabatan,'ketua')!==false);
    $sekre = $team->filter(fn($m)=>stripos($m->jabatan,'sekretaris')!==false);
    $koor  = $team->filter(fn($m)=>stripos($m->jabatan,'koordinator')!==false);
    $ang   = $team->filter(fn($m)=>stripos($m->jabatan,'anggota')!==false);
    $other = $team->filter(fn($m)=>
        stripos($m->jabatan,'penanggung jawab')===false &&
        stripos($m->jabatan,'ketua')===false &&
        stripos($m->jabatan,'sekretaris')===false &&
        stripos($m->jabatan,'koordinator')===false &&
        stripos($m->jabatan,'anggota')===false
    );
@endphp

<div class="text-center mb-4">
    <p class="section-eyebrow mb-1">Hierarki Organisasi</p>
    <h2 class="section-title mb-0">P2K3 PT PLN (Persero)</h2>
</div>

<div class="org-wrap">
<div class="org-tree">

{{-- ══ LEVEL 1: Penanggung Jawab ══ --}}
@foreach($pj as $m)
<div class="org-node node-pj">
    <i class="crown-icon bi bi-star-fill"></i>
    <div class="org-av">
        @if(!empty($m->foto))
            <img src="{{ $m->foto }}" alt="{{ $m->nama }}">
        @else
            <div class="org-av-icon"><i class="bi bi-person-badge-fill"></i></div>
        @endif
    </div>
    <div class="nd-jabatan">{{ $m->jabatan }}</div>
    @if(!empty($m->nama))<div class="nd-nama">{{ $m->nama }}</div>@endif
    @if(!empty($m->responsibility))<div class="nd-resp">{{ $m->responsibility }}</div>@endif
</div>
@endforeach

{{-- konektor --}}
@if($pj->isNotEmpty() && ($ketua->isNotEmpty() || $sekre->isNotEmpty()))
<div class="v-line"></div>
@endif

{{-- ══ LEVEL 2: Ketua + Sekretaris ══ --}}
@if($ketua->isNotEmpty() || $sekre->isNotEmpty())
<div class="h-row has-bar" style="--hl:20%;--hr:20%; gap:2rem;">
    @foreach($ketua as $m)
    <div class="col-node">
        <div class="org-node node-ketua">
            <div class="org-av">
                @if(!empty($m->foto))
                    <img src="{{ $m->foto }}" alt="{{ $m->nama }}">
                @else
                    <div class="org-av-icon"><i class="bi bi-person-workspace"></i></div>
                @endif
            </div>
            <div class="nd-jabatan">{{ $m->jabatan }}</div>
            @if(!empty($m->nama))<div class="nd-nama">{{ $m->nama }}</div>@endif
            @if(!empty($m->responsibility))<div class="nd-resp">{{ $m->responsibility }}</div>@endif
        </div>
    </div>
    @endforeach
    @foreach($sekre as $m)
    <div class="col-node">
        <div class="org-node node-sekretaris">
            <div class="org-av">
                @if(!empty($m->foto))
                    <img src="{{ $m->foto }}" alt="{{ $m->nama }}">
                @else
                    <div class="org-av-icon"><i class="bi bi-journal-text"></i></div>
                @endif
            </div>
            <div class="nd-jabatan">{{ $m->jabatan }}</div>
            @if(!empty($m->nama))<div class="nd-nama">{{ $m->nama }}</div>@endif
            @if(!empty($m->responsibility))<div class="nd-resp">{{ $m->responsibility }}</div>@endif
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- konektor --}}
@if($koor->isNotEmpty())
<div class="v-line"></div>
@endif

{{-- ══ LEVEL 3: Koordinator ══ --}}
@if($koor->isNotEmpty())
<div class="h-row has-bar" style="--hl:4%;--hr:4%; gap:.75rem; flex-wrap:wrap;">
    @foreach($koor as $m)
    <div class="col-node mb-3">
        <div class="org-node node-koordinator">
            <div class="org-av">
                @if(!empty($m->foto))
                    <img src="{{ $m->foto }}" alt="{{ $m->nama }}">
                @else
                    <div class="org-av-icon"><i class="bi bi-diagram-3"></i></div>
                @endif
            </div>
            <div class="nd-jabatan">{{ $m->jabatan }}</div>
            @if(!empty($m->nama))<div class="nd-nama">{{ $m->nama }}</div>@endif
            @if(!empty($m->responsibility))<div class="nd-resp">{{ $m->responsibility }}</div>@endif
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- konektor --}}
@if($ang->isNotEmpty())
<div class="v-line"></div>
@endif

{{-- ══ LEVEL 4: Anggota ══ --}}
@if($ang->isNotEmpty())
<div class="h-row has-bar" style="--hl:12%;--hr:12%; gap:.75rem; flex-wrap:wrap;">
    @foreach($ang as $m)
    <div class="col-node mb-3">
        <div class="org-node node-anggota">
            <div class="org-av">
                @if(!empty($m->foto))
                    <img src="{{ $m->foto }}" alt="{{ $m->nama }}">
                @else
                    <div class="org-av-icon"><i class="bi bi-person-fill"></i></div>
                @endif
            </div>
            <div class="nd-jabatan">{{ $m->jabatan }}</div>
            @if(!empty($m->nama))<div class="nd-nama">{{ $m->nama }}</div>@endif
            @if(!empty($m->responsibility))<div class="nd-resp">{{ $m->responsibility }}</div>@endif
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ══ Jabatan lain ══ --}}
@if($other->isNotEmpty())
<div class="v-line"></div>
<div class="h-row has-bar" style="--hl:12%;--hr:12%; gap:.75rem; flex-wrap:wrap;">
    @foreach($other as $m)
    <div class="col-node mb-3">
        <div class="org-node node-anggota">
            <div class="org-av">
                @if(!empty($m->foto))
                    <img src="{{ $m->foto }}" alt="{{ $m->nama }}">
                @else
                    <div class="org-av-icon"><i class="bi bi-person-circle"></i></div>
                @endif
            </div>
            <div class="nd-jabatan">{{ $m->jabatan }}</div>
            @if(!empty($m->nama))<div class="nd-nama">{{ $m->nama }}</div>@endif
            @if(!empty($m->responsibility))<div class="nd-resp">{{ $m->responsibility }}</div>@endif
        </div>
    </div>
    @endforeach
</div>
@endif

</div>{{-- org-tree --}}
</div>{{-- org-wrap --}}

{{-- LEGENDA --}}
<div class="border-top pt-4 mt-2">
    <p class="text-center text-muted small fw-semibold text-uppercase mb-3" style="letter-spacing:.07em;">Keterangan Level</p>
    <div class="org-legend">
        <div class="leg-item"><div class="leg-dot" style="background:var(--pln-blue-dark);border:2px solid var(--pln-yellow);"></div> Penanggung Jawab</div>
        <div class="leg-item"><div class="leg-dot" style="background:var(--pln-blue);"></div> Ketua P2K3</div>
        <div class="leg-item"><div class="leg-dot" style="background:#fff;border:2px solid var(--pln-sky);"></div> Sekretaris</div>
        <div class="leg-item"><div class="leg-dot" style="background:#fff;border:1.5px solid var(--line);"></div> Koordinator</div>
        <div class="leg-item"><div class="leg-dot" style="background:var(--surface);border:1.5px dashed #c8d0de;"></div> Anggota</div>
    </div>
</div>

@endif
</div>
</section>
@endsection