{{-- resources/views/laptops/_form.blade.php --}}
<div class="row g-3">
    <div class="col-12">
        <label class="form-label fw-semibold">Nama Laptop <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $laptop->name ?? '') }}"
               placeholder="Contoh: ASUS Vivobook S14 S3407CA">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price', $laptop->price ?? '') }}" placeholder="15000000" min="1000000">
        </div>
        @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">RAM (GB) <span class="text-danger">*</span></label>
        <select name="ram" class="form-select @error('ram') is-invalid @enderror">
            <option value="">-- Pilih RAM --</option>
            @foreach([4,8,16,32,64] as $r)
                <option value="{{ $r }}" {{ old('ram', $laptop->ram ?? '') == $r ? 'selected' : '' }}>{{ $r }} GB</option>
            @endforeach
        </select>
        @error('ram') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">CPU Score (PassMark) <span class="text-danger">*</span></label>
        <input type="number" name="cpu_score" class="form-control @error('cpu_score') is-invalid @enderror"
               value="{{ old('cpu_score', $laptop->cpu_score ?? '') }}" placeholder="19643" min="1">
        <div class="form-text"><a href="https://www.cpubenchmark.net" target="_blank">Cek skor di cpubenchmark.net</a></div>
        @error('cpu_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Bobot Perangkat (kg) <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" name="weight_kg" class="form-control @error('weight_kg') is-invalid @enderror"
                   value="{{ old('weight_kg', $laptop->weight_kg ?? '') }}" placeholder="1.4" step="0.01" min="0.1">
            <span class="input-group-text">kg</span>
        </div>
        @error('weight_kg') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Storage (GB) <span class="text-danger">*</span></label>
        <select name="storage" class="form-select @error('storage') is-invalid @enderror">
            <option value="">-- Pilih Storage --</option>
            @foreach([256,512,1024,2048] as $s)
                <option value="{{ $s }}" {{ old('storage', $laptop->storage ?? '') == $s ? 'selected' : '' }}>{{ $s >= 1024 ? ($s/1024).' TB' : $s.' GB' }}</option>
            @endforeach
        </select>
        @error('storage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Daya Tahan Baterai (jam) <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" name="battery" class="form-control @error('battery') is-invalid @enderror"
                   value="{{ old('battery', $laptop->battery ?? '') }}" placeholder="11" step="0.5" min="0.5">
            <span class="input-group-text">jam</span>
        </div>
        @error('battery') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
</div>
