{{-- resources/views/laptops/_form.blade.php — form dinamis berdasarkan kriteria aktif --}}
<div class="row g-3">
    <div class="col-12">
        <label class="form-label fw-semibold">Nama Laptop <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $laptop->name ?? '') }}"
               placeholder="Contoh: ASUS Vivobook S14 S3407CA">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @if($criteria->isEmpty())
        <div class="col-12">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Belum ada kriteria. Tambahkan kriteria terlebih dahulu di menu
                <a href="{{ route('criteria.index') }}">Kriteria &amp; Bobot</a>.
            </div>
        </div>
    @else
        <div class="col-12"><hr class="my-1"><small class="text-muted fw-semibold">Nilai per Kriteria</small></div>
        @foreach($criteria as $c)
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    {{ $c->name }}
                    @if($c->unit)<span class="text-muted fw-normal">({{ $c->unit }})</span>@endif
                    <span class="text-danger">*</span>
                    <span class="badge bg-{{ $c->type === 'benefit' ? 'success' : 'danger' }} ms-1">{{ ucfirst($c->type) }}</span>
                </label>
                <input type="number" step="any" min="0"
                       name="values[{{ $c->id }}]"
                       class="form-control @error('values.'.$c->id) is-invalid @enderror"
                       value="{{ old('values.'.$c->id, isset($laptop) ? $laptop->valueFor($c->id) : '') }}"
                       placeholder="Masukkan nilai {{ strtolower($c->name) }}">
                @error('values.'.$c->id) <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        @endforeach
    @endif
</div>
