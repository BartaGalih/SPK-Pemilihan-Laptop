@extends('layouts.app')
@section('title', 'Detail Laptop')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}">Data Laptop</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
        <h5 class="mb-0 fw-bold"><i class="bi bi-laptop text-info me-2"></i>{{ $laptop->name }}</h5>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('laptops.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header bg-transparent fw-semibold">Spesifikasi</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">Harga</th>
                        <td class="fw-bold text-success">Rp {{ number_format($laptop->price, 0, ',', '.') }}</td></tr>
                    <tr><th class="text-muted">RAM</th><td>{{ $laptop->ram }} GB</td></tr>
                    <tr><th class="text-muted">CPU Score (PassMark)</th><td>{{ number_format($laptop->cpu_score, 0, ',', '.') }}</td></tr>
                    <tr><th class="text-muted">Bobot</th><td>{{ $laptop->weight_kg }} kg</td></tr>
                    <tr><th class="text-muted">Storage</th><td>{{ $laptop->storage >= 1024 ? ($laptop->storage/1024).' TB' : $laptop->storage.' GB' }}</td></tr>
                    <tr><th class="text-muted">Baterai</th><td>{{ $laptop->battery }} jam</td></tr>
                </table>
            </div>
        </div>
    </div>

    @if($laptop->mfepResult)
    <div class="col-md-5">
        <div class="card h-100 border-primary">
            <div class="card-header bg-primary text-white fw-semibold">
                Hasil MFEP
                <span class="badge bg-warning text-dark float-end">Rank #{{ $laptop->mfepResult->rank }}</span>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-6 fw-bold text-primary">{{ number_format($laptop->mfepResult->tbe, 4) }}</div>
                    <small class="text-muted">Total Bobot Evaluasi (TBE)</small>
                </div>
                <table class="table table-sm">
                    <thead><tr><th>Kriteria</th><th class="text-center">NEF</th><th class="text-center">NBE</th></tr></thead>
                    <tbody>
                    @foreach(['c1'=>'Harga','c2'=>'RAM','c3'=>'CPU','c4'=>'Bobot','c5'=>'Storage','c6'=>'Baterai'] as $c => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td class="text-center">{{ $laptop->mfepResult->{"nef_{$c}"} }}</td>
                            <td class="text-center">{{ $laptop->mfepResult->{"nbe_{$c}"} }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-5">
        <div class="card h-100 border-dashed">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-muted">
                <i class="bi bi-calculator fs-1 mb-2"></i>
                <p class="mb-2">Belum ada hasil perhitungan MFEP</p>
                <a href="{{ route('results.index') }}" class="btn btn-sm btn-outline-primary">Hitung Sekarang</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
