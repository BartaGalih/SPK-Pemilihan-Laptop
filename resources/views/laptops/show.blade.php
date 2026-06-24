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
                    @forelse($criteria as $c)
                        <tr>
                            <th class="text-muted" style="width:45%">
                                {{ $c->name }}
                                <span class="badge bg-{{ $c->type === 'benefit' ? 'success' : 'danger' }} ms-1">{{ ucfirst($c->type) }}</span>
                            </th>
                            <td>{{ rtrim(rtrim(number_format($laptop->valueFor($c->id) ?? 0, 2, ',', '.'), '0'), ',') }}
                                @if($c->unit)<small class="text-muted">{{ $c->unit }}</small>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-muted">Belum ada kriteria.</td></tr>
                    @endforelse
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
                    @foreach($criteria as $c)
                        @php $d = $laptop->mfepResult->detailFor($c->id); @endphp
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td class="text-center">{{ $d ? number_format($d->nef, 2) : '—' }}</td>
                            <td class="text-center">{{ $d ? number_format($d->nbe, 4) : '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-5">
        <div class="card h-100">
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
