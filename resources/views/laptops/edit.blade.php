@extends('layouts.app')
@section('title', 'Edit Laptop')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}">Data Laptop</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Data Laptop</h5>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('laptops.update', $laptop) }}" method="POST">
            @csrf @method('PUT')
            @include('laptops._form')
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Perbarui
                </button>
                <a href="{{ route('laptops.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
