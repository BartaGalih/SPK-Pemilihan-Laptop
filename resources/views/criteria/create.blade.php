@extends('layouts.app')
@section('title', 'Tambah Kriteria')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('criteria.index') }}">Kriteria</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
    <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Kriteria Baru</h5>
</div>

<div class="card" style="max-width:560px">
    <div class="card-body p-4">
        <form action="{{ route('criteria.store') }}" method="POST">
            @csrf
            @include('criteria._form', ['criterium' => null])
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Simpan
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
@include('criteria._weight-script')
@endpush
