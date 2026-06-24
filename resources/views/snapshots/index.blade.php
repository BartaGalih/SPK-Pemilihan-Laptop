@extends('layouts.app')
@section('title', 'Arsip Hasil')

@section('content')
<div class="page-header">
    <h5 class="mb-0 fw-bold"><i class="bi bi-archive text-primary me-2"></i>Arsip Hasil Perhitungan</h5>
    <small class="text-muted">Kumpulan hasil perhitungan yang telah disimpan (salinan independen dari data master)</small>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Judul</th>
                    <th class="text-center">Laptop</th>
                    <th class="text-center">Kriteria</th>
                    <th>Disimpan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($snapshots as $s)
                <tr>
                    <td class="ps-3 text-muted">{{ $loop->iteration + ($snapshots->currentPage()-1)*$snapshots->perPage() }}</td>
                    <td class="fw-semibold">
                        {{ $s->title }}
                        @if($s->note)<div class="small text-muted">{{ \Illuminate\Support\Str::limit($s->note, 80) }}</div>@endif
                    </td>
                    <td class="text-center">{{ $s->total_laptops }}</td>
                    <td class="text-center">{{ $s->total_criteria }}</td>
                    <td class="text-muted small">{{ $s->created_at->format('d M Y, H:i') }}</td>
                    <td class="text-center text-nowrap">
                        <a href="{{ route('snapshots.show', $s) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('snapshots.destroy', $s) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus arsip ini secara permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada arsip. Simpan hasil dari halaman
                        <a href="{{ route('results.index') }}">Hasil Rekomendasi</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($snapshots->hasPages())
    <div class="card-footer bg-transparent">{{ $snapshots->links() }}</div>
    @endif
</div>
@endsection
