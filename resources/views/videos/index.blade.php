<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Video YouTube</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ url('/dashboard/admin') }}" class="brand-link">
            <span class="brand-text font-weight-light">Aplikasi</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                    @php
                        $role = Auth::user()->role ?? 'guest';
                        $menus = [
                            'admin' => [
                                ['url' => '/admin/users', 'icon' => 'fas fa-users', 'label' => 'Kelola User'],
                                ['url' => '/inventaris', 'icon' => 'fas fa-boxes', 'label' => 'Kelola Inventaris'],
                                ['url' => '/doa', 'icon' => 'fas fa-praying-hands', 'label' => 'Kelola Doa'],
                                ['url' => '/kalender', 'icon' => 'fas fa-calendar', 'label' => 'Kelola Kalender'],
                                ['url' => '/rekapan', 'icon' => 'fas fa-clipboard-list', 'label' => 'Rekapan'],
                                ['url' => '/admin/videos', 'icon' => 'fas fa-video', 'label' => 'Video'], 
                            ],
                            'tu' => [
                                ['url' => '/kalender', 'icon' => 'fas fa-calendar', 'label' => 'Kelola Kalender'],
                                ['url' => '/rekapan', 'icon' => 'fas fa-clipboard-list', 'label' => 'Rekapan'],
                            ],
                            'kabid' => [
                                ['url' => '/verifikasi', 'icon' => 'fas fa-check-circle', 'label' => 'Verifikasi Data'],
                            ],
                        ];
                    @endphp

                    @foreach($menus[$role] ?? [] as $menu)
                        <li class="nav-item">
                            <a href="{{ url($menu['url']) }}" class="nav-link {{ request()->is(ltrim($menu['url'], '/').'*') ? 'active' : '' }}">
                                <i class="nav-icon {{ $menu['icon'] }}"></i>
                                <p>{{ $menu['label'] }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content -->
    <div class="content-wrapper">
        <section class="content-header px-3 py-2">
            <h1>Daftar Video YouTube</h1>
        </section>

        <section class="content px-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold">Daftar Video</h3>
                    <a href="{{ route('videos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Video
                    </a>
                </div>

                <div class="card-body">
                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('videos.index') }}" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Cari video..." value="{{ request('q') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    {{-- Daftar Video --}}
                    <div class="row">
                        @forelse ($videos as $video)
                            <div class="col-md-6 mb-4">
                                <div class="card shadow-sm">
                                    <div class="ratio ratio-16x9">
                                        <iframe 
                                            src="{{ str_replace('watch?v=', 'embed/', $video->youtube_url) }}" 
                                            title="{{ $video->title }}" 
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $video->title }}</h5>
                                        <p class="card-text text-muted">{{ $video->description ?? 'Tidak ada deskripsi.' }}</p>
                                        <a href="{{ $video->youtube_url }}" target="_blank" class="btn btn-sm btn-danger">
                                            <i class="fab fa-youtube"></i> Lihat di YouTube
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center">
                                <p class="text-muted">Belum ada video.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
