@extends('layouts.app')
@section('title', 'Kriteria & Bobot')

@section('content')
@php
    $isValid   = abs($totalWeight - 1.0) < 0.001;
    $remaining = round(1 - $totalWeight, 2);
@endphp

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-sliders text-primary me-2"></i>Manajemen Kriteria &amp; Bobot (NBF)</h5>
        <small class="text-muted">Total bobot harus berjumlah 1.00 (100%)</small>
    </div>
    <a href="{{ route('criteria.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kriteria
    </a>
</div>

<div class="alert alert-{{ $isValid ? 'success' : 'warning' }} d-flex align-items-center gap-2">
    <i class="bi bi-{{ $isValid ? 'check-circle-fill' : 'exclamation-triangle-fill' }}"></i>
    <div>
        Total bobot saat ini: <strong>{{ number_format($totalWeight, 2) }}</strong>
        @if(!$isValid)
            — sisa <strong>{{ number_format($remaining, 2) }}</strong>.
            <em>Harap sesuaikan agar total = 1.00 sebelum menjalankan perhitungan.</em>
        @else
            — siap untuk dihitung.
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
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
                @forelse($criteria as $c)
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
                        <div class="progress" style="height:6px">
                            <div class="progress-bar" style="width:{{ $c->weight * 100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ round($c->weight * 100) }}%</small>
                    </td>
                    <td class="text-center text-nowrap">
                        <a href="{{ route('criteria.edit', $c) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('criteria.destroy', $c) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus kriteria \'{{ $c->name }}\'? Kriteria tidak lagi dipakai dalam perhitungan. Jangan lupa sesuaikan bobot agar total = 1.00.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada kriteria.
                        <a href="{{ route('criteria.create') }}">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    <div class="card">
        <div class="card-body">
            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle text-info me-2"></i>Keterangan</h6>
            <ul class="mb-0 small text-muted">
                <li><strong>Benefit</strong>: Nilai lebih besar = lebih baik (mis. RAM, CPU Score, Storage, Baterai)</li>
                <li><strong>Cost</strong>: Nilai lebih kecil = lebih baik (mis. Harga, Bobot perangkat)</li>
                <li>Saat menambah/mengubah kriteria, bobot dibatasi maksimal sisa bobot yang tersedia.</li>
                <li>NEF dihitung dengan normalisasi ranking skala 1–5; NBE = NBF × NEF; TBE = Σ NBE</li>
            </ul>
        </div>
    </div>
</div>
@endsection
