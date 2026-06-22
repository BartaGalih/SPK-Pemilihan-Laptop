@extends('layouts.app')
@section('title', 'Kriteria & Bobot')

@section('content')
<div class="page-header">
    <h5 class="mb-0 fw-bold"><i class="bi bi-sliders text-primary me-2"></i>Manajemen Kriteria & Bobot (NBF)</h5>
    <small class="text-muted">Total bobot harus berjumlah 1.00 (100%)</small>
</div>

@php
    $totalWeight = $criteria->sum('weight');
    $isValid = abs($totalWeight - 1.0) < 0.001;
@endphp

<div class="alert alert-{{ $isValid ? 'success' : 'warning' }} d-flex align-items-center gap-2">
    <i class="bi bi-{{ $isValid ? 'check-circle-fill' : 'exclamation-triangle-fill' }}"></i>
    <div>
        Total bobot saat ini: <strong>{{ number_format($totalWeight, 2) }}</strong>
        @if(!$isValid) — <em>Harap sesuaikan agar total = 1.00</em> @endif
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Kode</th>
                    <th>Nama Kriteria</th>
                    <th>Satuan</th>
                    <th>Tipe</th>
                    <th>Bobot (NBF)</th>
                    <th>Proporsi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criteria as $c)
                <tr>
                    <td class="ps-3"><span class="badge bg-secondary">{{ $c->code }}</span></td>
                    <td class="fw-semibold">{{ $c->name }}</td>
                    <td class="text-muted">{{ $c->unit }}</td>
                    <td>
                        @if($c->type === 'benefit')
                            <span class="badge bg-success"><i class="bi bi-arrow-up me-1"></i>Benefit</span>
                        @else
                            <span class="badge bg-danger"><i class="bi bi-arrow-down me-1"></i>Cost</span>
                        @endif
                    </td>
                    <td><strong>{{ number_format($c->weight, 2) }}</strong></td>
                    <td style="min-width:120px">
                        <div class="progress">
                            <div class="progress-bar" style="width:{{ $c->weight * 100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ round($c->weight * 100) }}%</small>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('criteria.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Edit Bobot
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    <div class="card">
        <div class="card-body">
            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle text-info me-2"></i>Keterangan</h6>
            <ul class="mb-0 small text-muted">
                <li><strong>Benefit</strong>: Nilai lebih besar = lebih baik (RAM, CPU Score, Storage, Baterai)</li>
                <li><strong>Cost</strong>: Nilai lebih kecil = lebih baik (Harga, Bobot perangkat)</li>
                <li>NEF dihitung dengan normalisasi ranking skala 1–5</li>
                <li>NBE = NBF × NEF; TBE = Σ NBE</li>
            </ul>
        </div>
    </div>
</div>
@endsection
