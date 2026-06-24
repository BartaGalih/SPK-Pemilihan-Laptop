@extends('layouts.app')
@section('title', 'Data Laptop')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-hdd-stack text-primary me-2"></i>Data Laptop</h5>
        <small class="text-muted">Manajemen data alternatif laptop untuk perhitungan MFEP</small>
    </div>
    <a href="{{ route('laptops.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Laptop
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nama Laptop</th>
                        @foreach($criteria as $c)
                            <th>{{ $c->name }} @if($c->unit)<small class="text-muted">({{ $c->unit }})</small>@endif</th>
                        @endforeach
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laptops as $laptop)
                    <tr>
                        <td class="ps-3 text-muted">{{ $loop->iteration + ($laptops->currentPage()-1)*$laptops->perPage() }}</td>
                        <td class="fw-semibold">{{ $laptop->name }}</td>
                        @foreach($criteria as $c)
                            <td>{{ rtrim(rtrim(number_format($laptop->valueFor($c->id) ?? 0, 2, ',', '.'), '0'), ',') }}</td>
                        @endforeach
                        <td class="text-center text-nowrap">
                            <a href="{{ route('laptops.show', $laptop) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('laptops.destroy', $laptop) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Hapus laptop ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $criteria->count() }}" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada data laptop.
                            <a href="{{ route('laptops.create') }}">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($laptops->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Menampilkan {{ $laptops->firstItem() }}–{{ $laptops->lastItem() }} dari {{ $laptops->total() }} data
        </small>
        {{ $laptops->links() }}
    </div>
    @endif
</div>
@endsection
