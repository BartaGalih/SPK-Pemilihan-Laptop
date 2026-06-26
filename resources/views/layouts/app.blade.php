<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Rekomendasi Laptop – @yield('title', 'Dashboard')</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(160deg, #0d47a1 0%, #1976d2 100%);
            color: #fff;
            width: 240px;
            flex-shrink: 0;
        }
        .sidebar .brand {
            padding: 1.4rem 1.25rem 1rem;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .5px;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: .55rem 1.25rem;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: .9rem;
            transition: background .15s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.18);
            color: #fff;
        }
        .sidebar .nav-link i { width: 20px; }

        /* Main */
        .main-content { flex: 1; min-width: 0; padding: 1.5rem; }

        .page-header {
            background: #fff;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
        }

        /* Badge rank */
        .rank-badge {
            width: 30px; height: 30px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem;
        }
        .rank-1 { background: #ffd700; color: #333; }
        .rank-2 { background: #c0c0c0; color: #333; }
        .rank-3 { background: #cd7f32; color: #fff; }
        .rank-other { background: #e9ecef; color: #555; }

        /* Tabel */
        .table th { font-size: .82rem; text-transform: uppercase; letter-spacing: .4px; color: #6c757d; }
        .table td { vertical-align: middle; font-size: .9rem; }
        .table-hover tbody tr:hover { background: #f8f9ff; }

        .progress { height: 8px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column py-2">
        <div class="brand">
            <i class="bi bi-laptop me-2"></i>SPK Laptop
        </div>
        <div class="mt-3 px-1">
            <small class="text-uppercase px-3" style="opacity:.5;font-size:.7rem;letter-spacing:1px">Menu</small>
            <ul class="nav flex-column mt-1">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laptops.index') }}"
                       class="nav-link {{ request()->routeIs('laptops.*') ? 'active' : '' }}">
                        <i class="bi bi-hdd-stack me-2"></i>Data Laptop
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('criteria.index') }}"
                       class="nav-link {{ request()->routeIs('criteria.*') ? 'active' : '' }}">
                        <i class="bi bi-sliders me-2"></i>Kriteria & Bobot
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('results.index') }}"
                       class="nav-link {{ request()->routeIs('results.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-steps me-2"></i>Hasil Rekomendasi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('snapshots.index') }}"
                       class="nav-link {{ request()->routeIs('snapshots.*') ? 'active' : '' }}">
                        <i class="bi bi-archive me-2"></i>Arsip Hasil
                    </a>
                </li>
            </ul>
        </div>

        <div class="mt-auto px-3 pb-3" style="font-size:.75rem;opacity:.5;">
            <hr style="border-color:rgba(255,255,255,.2)">
            Kelompok 5 – Informatika<br>
            Univ. Mercu Buana Yogyakarta<br>
            T.A. 2025/2026
        </div>
    </nav>

    <!-- Content -->
    <main class="main-content">
        <!-- Flash messages -->
        @foreach (['success','warning','danger','info'] as $type)
            @if(session($type))
                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                    <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'warning' ? 'exclamation-triangle' : 'info-circle') }} me-2"></i>
                    {{ session($type) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
