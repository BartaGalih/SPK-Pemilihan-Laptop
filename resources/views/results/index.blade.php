@extends('layouts.app')
@section('title', 'Hasil Rekomendasi MFEP')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart-steps text-primary me-2"></i>Hasil Rekomendasi MFEP</h5>
        <small class="text-muted">Perangkingan laptop berdasarkan Total Bobot Evaluasi (TBE)</small>
    </div>
    <form action="{{ route('results.calculate') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-calculator me-1"></i>Hitung / Perbarui MFEP
        </button>
    </form>
</div>

@if($results->isEmpty())
<div class="card">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-calculator fs-1 d-block mb-3"></i>
        <h6>Belum ada hasil perhitungan</h6>
        <p class="small mb-3">Pastikan data laptop dan kriteria sudah diisi, lalu klik <strong>Hitung / Perbarui MFEP</strong>.</p>
        <form action="{{ route('results.calculate') }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-primary">
                <i class="bi bi-calculator me-1"></i>Hitung Sekarang
            </button>
        </form>
    </div>
</div>
@else

{{-- ======= TOP 3 CARDS ======= --}}
@if($results->count() >= 3)
<div class="row g-3 mb-4">
    @foreach($results->take(3) as $r)
    @php
        $colors = ['1' => ['bg'=>'warning','icon'=>'trophy-fill','label'=>'Terbaik'],
                   '2' => ['bg'=>'secondary','icon'=>'award-fill','label'=>'Runner-up'],
                   '3' => ['bg'=>'danger','icon'=>'star-fill','label'=>'Pilihan ke-3']];
        $c = $colors[$r->rank] ?? ['bg'=>'light','icon'=>'star','label'=>''];
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
                <h6 class="fw-bold mb-1" style="font-size:.9rem">{{ $r->laptop->name }}</h6>
                <div class="text-muted small mb-2">Rp {{ number_format($r->laptop->price, 0, ',', '.') }}</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">TBE</span>
                    <span class="fs-5 fw-bold text-primary">{{ number_format($r->tbe, 4) }}</span>
                </div>
                <div class="progress mt-2">
                    <div class="progress-bar bg-{{ $c['bg'] }}"
                         style="width:{{ ($r->tbe / $results->first()->tbe) * 100 }}%"></div>
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
        <i class="bi bi-table text-primary"></i>
        Matriks Evaluasi Lengkap
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
                            {{ $c->code }}<br>
                            <small class="fw-normal text-muted">{{ $c->name }}</small>
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
                            <span class="rank-badge rank-{{ $r->rank <= 3 ? $r->rank : 'other' }}">
                                {{ $r->rank }}
                            </span>
                        </td>
                        <td class="text-start fw-semibold">{{ $r->laptop->name }}</td>
                        @foreach(['c1','c2','c3','c4','c5','c6'] as $cCode)
                        <td>{{ number_format($r->{"nef_{$cCode}"}, 2) }}</td>
                        <td class="text-muted">{{ number_format($r->{"nbe_{$cCode}"}, 4) }}</td>
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
                <tr>
                    <th class="ps-3">Kode</th>
                    <th>Kriteria</th>
                    <th>Tipe</th>
                    <th>Bobot (NBF)</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criteria as $c)
                <tr>
                    <td class="ps-3"><span class="badge bg-secondary">{{ $c->code }}</span></td>
                    <td>{{ $c->name }}</td>
                    <td>
                        <span class="badge bg-{{ $c->type === 'benefit' ? 'success' : 'danger' }}">
                            {{ ucfirst($c->type) }}
                        </span>
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

@endif
@endsection
