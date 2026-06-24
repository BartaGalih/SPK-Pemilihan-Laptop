@extends('layouts.app')
@section('title', 'Hasil Rekomendasi MFEP')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart-steps text-primary me-2"></i>Hasil Rekomendasi MFEP</h5>
        <small class="text-muted">Perangkingan laptop berdasarkan Total Bobot Evaluasi (TBE)</small>
    </div>
    <div class="d-flex gap-2">
        @if(!$results->isEmpty())
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#saveSnapshotModal">
            <i class="bi bi-archive me-1"></i>Simpan ke Arsip
        </button>
        @endif
        <form action="{{ route('results.calculate') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" {{ $weightValid ? '' : 'disabled' }}>
                <i class="bi bi-calculator me-1"></i>Hitung / Perbarui MFEP
            </button>
        </form>
    </div>
</div>

@if(!$weightValid)
<div class="alert alert-warning d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>
        Tombol hitung dinonaktifkan karena total bobot kriteria = <strong>{{ number_format($totalWeight, 2) }}</strong>
        (harus = 1.00). Perbaiki di menu <a href="{{ route('criteria.index') }}">Kriteria &amp; Bobot</a>.
    </div>
</div>
@endif

@if($results->isEmpty())
<div class="card">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-calculator fs-1 d-block mb-3"></i>
        <h6>Belum ada hasil perhitungan</h6>
        <p class="small mb-3">Pastikan data laptop dan kriteria sudah diisi (total bobot = 1.00), lalu klik <strong>Hitung / Perbarui MFEP</strong>.</p>
    </div>
</div>
@else

{{-- ======= TOP 3 CARDS ======= --}}
@if($results->count() >= 3)
<div class="row g-3 mb-4">
    @foreach($results->take(3) as $r)
    @php
        $meta = [1 => ['bg'=>'warning','icon'=>'trophy-fill','label'=>'Terbaik'],
                 2 => ['bg'=>'secondary','icon'=>'award-fill','label'=>'Runner-up'],
                 3 => ['bg'=>'danger','icon'=>'star-fill','label'=>'Pilihan ke-3']];
        $c = $meta[$r->rank] ?? ['bg'=>'light','icon'=>'star','label'=>''];
        $top = $results->first()->tbe ?: 1;
    @endphp
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-top: 4px solid var(--bs-{{ $c['bg'] }}) !important;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="rank-badge rank-{{ $r->rank }} me-2">{{ $r->rank }}</span>
                    <span class="badge bg-{{ $c['bg'] }} text-{{ in_array($c['bg'],['warning','light']) ? 'dark' : 'white' }}">
                        <i class="bi bi-{{ $c['icon'] }} me-1"></i>{{ $c['label'] }}
                    </span>
                </div>
                <h6 class="fw-bold mb-2" style="font-size:.9rem">{{ $r->laptop->name }}</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">TBE</span>
                    <span class="fs-5 fw-bold text-primary">{{ number_format($r->tbe, 4) }}</span>
                </div>
                <div class="progress mt-2">
                    <div class="progress-bar bg-{{ $c['bg'] }}" style="width:{{ ($r->tbe / $top) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ======= TABEL DETAIL NEF / NBE ======= --}}
<div class="card mb-4">
    <div class="card-header bg-transparent fw-semibold d-flex align-items-center gap-2">
        <i class="bi bi-table text-primary"></i> Matriks Evaluasi Lengkap
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 text-center" style="font-size:.82rem">
                <thead class="table-primary">
                    <tr>
                        <th rowspan="2" class="align-middle text-start ps-3">Rank</th>
                        <th rowspan="2" class="align-middle text-start">Nama Laptop</th>
                        @foreach($criteria as $c)
                        <th colspan="2" class="text-center">
                            {{ $c->code }}<br><small class="fw-normal text-muted">{{ $c->name }}</small>
                        </th>
                        @endforeach
                        <th rowspan="2" class="align-middle">TBE</th>
                    </tr>
                    <tr>
                        @foreach($criteria as $c)
                        <th class="bg-light" style="font-size:.75rem">NEF</th>
                        <th class="bg-light" style="font-size:.75rem">NBE</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $r)
                    <tr class="{{ $r->rank === 1 ? 'table-warning' : '' }}">
                        <td class="text-start ps-3">
                            <span class="rank-badge rank-{{ $r->rank <= 3 ? $r->rank : 'other' }}">{{ $r->rank }}</span>
                        </td>
                        <td class="text-start fw-semibold">{{ $r->laptop->name }}</td>
                        @foreach($criteria as $c)
                            @php $d = $r->detailFor($c->id); @endphp
                            <td>{{ $d ? number_format($d->nef, 2) : '—' }}</td>
                            <td class="text-muted">{{ $d ? number_format($d->nbe, 4) : '—' }}</td>
                        @endforeach
                        <td class="fw-bold text-primary">{{ number_format($r->tbe, 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======= TABEL BOBOT KRITERIA ======= --}}
<div class="card">
    <div class="card-header bg-transparent fw-semibold">
        <i class="bi bi-sliders text-secondary me-2"></i>Bobot Kriteria (NBF) yang Digunakan
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr><th class="ps-3">Kode</th><th>Kriteria</th><th>Tipe</th><th>Bobot (NBF)</th><th>%</th></tr>
            </thead>
            <tbody>
                @foreach($criteria as $c)
                <tr>
                    <td class="ps-3"><span class="badge bg-secondary">{{ $c->code }}</span></td>
                    <td>{{ $c->name }}</td>
                    <td>
                        <span class="badge bg-{{ $c->type === 'benefit' ? 'success' : 'danger' }}">{{ ucfirst($c->type) }}</span>
                    </td>
                    <td>{{ number_format($c->weight, 2) }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px">
                                <div class="progress-bar" style="width:{{ $c->weight*100 }}%"></div>
                            </div>
                            {{ round($c->weight*100) }}%
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ======= MODAL SIMPAN ARSIP ======= --}}
<div class="modal fade" id="saveSnapshotModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('results.snapshot') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title"><i class="bi bi-archive me-2"></i>Simpan Hasil ke Arsip</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">
                        Hasil yang disimpan adalah <strong>salinan</strong> (laptop, kriteria, bobot, dan hasil hitung saat ini).
                        Perubahan data master setelah ini tidak akan mengubah arsip.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Arsip <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required
                               placeholder="Contoh: Rekomendasi Laptop Mahasiswa — Juni 2026">
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan Arsip</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif
@endsection
