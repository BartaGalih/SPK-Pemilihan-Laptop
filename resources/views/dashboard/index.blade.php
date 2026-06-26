@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard</h5>
        <small class="text-muted">Ringkasan cepat data SPK Rekomendasi Laptop</small>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="mb-1">Laptop</h6>
                        <p class="text-muted small mb-0">Total data laptop</p>
                    </div>
                    <span class="badge bg-primary rounded-pill fs-6">{{ $totalLaptops }}</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="mb-1">Kriteria</h6>
                        <p class="text-muted small mb-0">Total kriteria aktif</p>
                    </div>
                    <span class="badge bg-success rounded-pill fs-6">{{ $totalCriteria }}</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="mb-1">Hasil</h6>
                        <p class="text-muted small mb-0">Rekomendasi terakhir</p>
                    </div>
                    <span class="badge bg-warning rounded-pill fs-6">{{ $totalResults }}</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="mb-1">Arsip</h6>
                        <p class="text-muted small mb-0">Snapshot tersimpan</p>
                    </div>
                    <span class="badge bg-secondary rounded-pill fs-6">{{ $totalSnapshots }}</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-secondary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Status Bobot Kriteria</h6>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="mb-1">Total Bobot Saat Ini</p>
                        <h4 class="mb-0">{{ number_format($totalWeight, 2) }}</h4>
                    </div>
                    <span class="badge bg-{{ $weightValid ? 'success' : 'danger' }} text-white">
                        {{ $weightValid ? 'Valid' : 'Tidak Valid' }}
                    </span>
                </div>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-{{ $weightValid ? 'success' : 'danger' }}" style="width: {{ min(100, max(0, $totalWeight * 100)) }}%"></div>
                </div>
                <p class="small text-muted mb-0">Pastikan total bobot kriteria = 1.00 sebelum menghitung rekomendasi.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Aktivitas Terbaru</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="small text-muted">Perhitungan terakhir</div>
                        <div>{{ $lastCalculatedAt ? $lastCalculatedAt->translatedFormat('d F Y H:i') : 'Belum pernah dihitung' }}</div>
                    </li>
                    <li>
                        <div class="small text-muted">Snapshot terakhir</div>
                        <div>{{ $latestSnapshot ? $latestSnapshot->title . ' (' . $latestSnapshot->created_at->format('d M Y') . ')' : 'Belum ada snapshot' }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@if($topResults->isNotEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="fw-semibold mb-0">Top 3 Rekomendasi</h6>
                <small class="text-muted">Berdasarkan hasil MFEP terakhir</small>
            </div>
            <a href="{{ route('results.index') }}" class="btn btn-sm btn-outline-primary">Lihat detail</a>
        </div>
        <div class="row g-3">
            @foreach($topResults as $result)
            <div class="col-md-4">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="small text-muted">Rank {{ $result->rank }}</div>
                                <h6 class="mb-0">{{ $result->laptop->name }}</h6>
                            </div>
                            <span class="badge bg-primary">TBE</span>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <div>
                                <div class="fs-5 fw-bold">{{ number_format($result->tbe, 4) }}</div>
                                <div class="text-muted small">Hasil MFEP</div>
                            </div>
                            <span class="rank-badge rank-{{ $result->rank <= 3 ? $result->rank : 'other' }}">{{ $result->rank }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
