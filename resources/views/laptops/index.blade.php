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
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nama Laptop</th>
                        <th>Harga (Rp)</th>
                        <th>RAM</th>
                        <th>CPU Score</th>
                        <th>Bobot</th>
                        <th>Storage</th>
                        <th>Baterai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laptops as $laptop)
                    <tr>
                        <td class="ps-3 text-muted">{{ $loop->iteration + ($laptops->currentPage()-1)*$laptops->perPage() }}</td>
                        <td class="fw-semibold">{{ $laptop->name }}</td>
                        <td>{{ number_format($laptop->price, 0, ',', '.') }}</td>
                        <td><span class="badge bg-info text-dark">{{ $laptop->ram }} GB</span></td>
                        <td>{{ number_format($laptop->cpu_score, 0, ',', '.') }}</td>
                        <td>{{ $laptop->weight_kg }} kg</td>
                        <td>{{ number_format($laptop->storage) }} GB</td>
                        <td>{{ $laptop->battery }} jam</td>
                        <td class="text-center">
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
                        <td colspan="9" class="text-center py-4 text-muted">
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
