@extends('layouts.app')
@section('title', 'Edit Kriteria')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('criteria.index') }}">Kriteria</a></li>
            <li class="breadcrumb-item active">Edit {{ $criterium->code }}</li>
        </ol>
    </nav>
    <h5 class="mb-0 fw-bold">
        <i class="bi bi-pencil-square text-warning me-2"></i>Edit Kriteria
        <span class="badge bg-secondary ms-2">{{ $criterium->code }}</span>
    </h5>
</div>

<div class="card" style="max-width:540px">
    <div class="card-body p-4">
        <form action="{{ route('criteria.update', $criterium) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kriteria</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $criterium->name) }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Satuan</label>
                <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror"
                       value="{{ old('unit', $criterium->unit) }}" placeholder="Rp / GB / kg / jam">
                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="benefit" {{ old('type', $criterium->type) === 'benefit' ? 'selected' : '' }}>Benefit (nilai besar = baik)</option>
                    <option value="cost"    {{ old('type', $criterium->type) === 'cost'    ? 'selected' : '' }}>Cost (nilai kecil = baik)</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Bobot (NBF) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" name="weight" id="weightInput"
                           class="form-control @error('weight') is-invalid @enderror"
                           value="{{ old('weight', $criterium->weight) }}"
                           step="0.01" min="0.01" max="1">
                    <span class="input-group-text" id="weightPct">
                        {{ round($criterium->weight * 100) }}%
                    </span>
                </div>
                <div class="form-text">Masukkan antara 0.01 – 1.00 (total semua bobot = 1.00)</div>
                @error('weight') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                </button>
                <a href="{{ route('criteria.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const inp = document.getElementById('weightInput');
    const pct = document.getElementById('weightPct');
    inp.addEventListener('input', () => {
        pct.textContent = Math.round(parseFloat(inp.value || 0) * 100) + '%';
    });
</script>
@endpush
