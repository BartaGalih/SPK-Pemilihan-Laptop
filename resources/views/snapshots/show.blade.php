@extends('layouts.app')
@section('title', 'Detail Arsip')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('snapshots.index') }}">Arsip Hasil</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
        <h5 class="mb-0 fw-bold"><i class="bi bi-archive text-primary me-2"></i>{{ $snapshot->title }}</h5>
        <small class="text-muted">
            Disimpan {{ $snapshot->created_at->format('d M Y, H:i') }} ·
            {{ $snapshot->total_laptops }} laptop · {{ $snapshot->total_criteria }} kriteria
        </small>
    </div>
    <a href="{{ route('snapshots.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

@if($snapshot->note)
<div class="alert alert-light border"><i class="bi bi-sticky me-2"></i>{{ $snapshot->note }}</div>
@endif

{{-- Bobot kriteria (salinan) --}}
<div class="card mb-4">
    <div class="card-header bg-transparent fw-semibold"><i class="bi bi-sliders text-secondary me-2"></i>Kriteria &amp; Bobot (Salinan)</div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr><th class="ps-3">Kode</th><th>Kriteria</th><th>Satuan</th><th>Tipe</th><th>Bobot</th></tr>
            </thead>
            <tbody>
                @foreach($snapshot->criteria as $c)
                <tr>
                    <td class="ps-3"><span class="badge bg-secondary">{{ $c->code }}</span></td>
                    <td>{{ $c->name }}</td>
                    <td class="text-muted">{{ $c->unit }}</td>
                    <td><span class="badge bg-{{ $c->type === 'benefit' ? 'success' : 'danger' }}">{{ ucfirst($c->type) }}</span></td>
                    <td>{{ number_format($c->weight, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Matriks hasil (salinan) --}}
<div class="card">
    <div class="card-header bg-transparent fw-semibold"><i class="bi bi-table text-primary me-2"></i>Hasil Perhitungan (Salinan)</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center align-middle" style="font-size:.82rem">
                <thead class="table-primary">
                    <tr>
                        <th rowspan="2" class="align-middle text-start ps-3">Rank</th>
                        <th rowspan="2" class="align-middle text-start">Nama Laptop</th>
                        @foreach($snapshot->criteria as $c)
                        <th colspan="3">{{ $c->code }}<br><small class="fw-normal text-muted">{{ $c->name }}</small></th>
                        @endforeach
                        <th rowspan="2" class="align-middle">TBE</th>
                    </tr>
                    <tr>
                        @foreach($snapshot->criteria as $c)
                        <th class="bg-light" style="font-size:.72rem">Nilai</th>
                        <th class="bg-light" style="font-size:.72rem">NEF</th>
                        <th class="bg-light" style="font-size:.72rem">NBE</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($snapshot->laptops as $lp)
                    <tr class="{{ $lp->rank === 1 ? 'table-warning' : '' }}">
                        <td class="text-start ps-3">
                            <span class="rank-badge rank-{{ $lp->rank <= 3 ? $lp->rank : 'other' }}">{{ $lp->rank }}</span>
                        </td>
                        <td class="text-start fw-semibold">{{ $lp->name }}</td>
                        @foreach($snapshot->criteria as $c)
                            @php $v = $lp->values->firstWhere('snapshot_criteria_id', $c->id); @endphp
                            <td>{{ $v ? rtrim(rtrim(number_format($v->value, 2, ',', '.'), '0'), ',') : '—' }}</td>
                            <td>{{ $v ? number_format($v->nef, 2) : '—' }}</td>
                            <td class="text-muted">{{ $v ? number_format($v->nbe, 4) : '—' }}</td>
                        @endforeach
                        <td class="fw-bold text-primary">{{ number_format($lp->tbe, 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
