{{-- resources/views/criteria/_form.blade.php — dipakai create & edit --}}
@php
    $cur = $criterium ?? null;
@endphp

@if($remaining <= 0 && !$cur)
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>Total bobot sudah <strong>1.00</strong>. Kurangi bobot kriteria lain dulu sebelum menambah kriteria baru.</div>
    </div>
@endif

<div class="mb-3">
    <label class="form-label fw-semibold">Nama Kriteria <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $cur->name ?? '') }}" placeholder="Contoh: Harga, RAM, Kualitas Layar">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Satuan</label>
    <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror"
           value="{{ old('unit', $cur->unit ?? '') }}" placeholder="Rp / GB / kg / jam">
    @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
    <select name="type" class="form-select @error('type') is-invalid @enderror">
        <option value="benefit" {{ old('type', $cur->type ?? 'benefit') === 'benefit' ? 'selected' : '' }}>Benefit (nilai besar = baik)</option>
        <option value="cost"    {{ old('type', $cur->type ?? '') === 'cost' ? 'selected' : '' }}>Cost (nilai kecil = baik)</option>
    </select>
    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Bobot (NBF) <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="number" name="weight" id="weightInput"
               class="form-control @error('weight') is-invalid @enderror"
               value="{{ old('weight', $cur->weight ?? '') }}"
               step="0.01" min="0.01" max="{{ $remaining }}"
               data-remaining="{{ $remaining }}">
        <span class="input-group-text" id="weightPct">{{ round(($cur->weight ?? 0) * 100) }}%</span>
    </div>
    <div class="form-text">
        Sisa bobot yang tersedia: <strong id="remainText">{{ number_format($remaining, 2) }}</strong>
        (maksimal bobot yang boleh dimasukkan). Total semua bobot harus = 1.00.
    </div>
    <div id="weightWarn" class="text-danger small mt-1 d-none">
        <i class="bi bi-exclamation-triangle me-1"></i>Bobot melebihi sisa yang tersedia. Kurangi bobot kriteria lain dulu.
    </div>
    @error('weight') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>
