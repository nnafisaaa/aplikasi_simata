<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <a href="{{ url('/dashboard') }}" class="brand-link">
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
                            <a href="{{ url($menu['url']) }}" class="nav-link">
                                <i class="nav-icon {{ $menu['icon'] }}"></i>
                                <p>{{ $menu['label'] }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header px-3 py-2">
            <h1>Dashboard Utama</h1>
        </section>

        <section class="content px-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Selamat datang, {{ Auth::user()->username }}</h3>
                        </div>
                        <div class="card-body">
                            <p><b>Role Anda:</b> {{ ucfirst(Auth::user()->role) }}</p>

                            @if(Auth::user()->role === 'admin')
                                <a href="{{ url('/admin/users') }}" class="btn btn-primary mb-2">
                                    <i class="fas fa-users"></i> Kelola User
                                </a>
                                <a href="{{ url('/inventaris') }}" class="btn btn-success mb-2">
                                    <i class="fas fa-boxes"></i> Kelola Inventaris
                                </a>
                                <a href="{{ url('/doa') }}" class="btn btn-warning mb-2">
                                    <i class="fas fa-praying-hands"></i> Kelola Doa
                                </a>
                                <a href="{{ url('/kalender') }}" class="btn btn-info mb-2">
                                    <i class="fas fa-calendar"></i> Kelola Kalender
                                </a>
                                <a href="{{ url('/rekapan') }}" class="btn btn-dark mb-2">
                                    <i class="fas fa-clipboard-list"></i> Rekapan
                                </a>
                                
                                <a href="{{ url('/admin/videos') }}" class="btn btn-danger mb-2">
                                    <i class="fas fa-video"></i> Video
                                </a>
                            @elseif(Auth::user()->role === 'tu')
                                <a href="{{ url('/kalender') }}" class="btn btn-warning mb-2">
                                    <i class="fas fa-calendar"></i> Kelola Kalender
                                </a>
                            @elseif(Auth::user()->role === 'kabid')
                                <a href="{{ url('/verifikasi') }}" class="btn btn-info mb-2">
                                    <i class="fas fa-check-circle"></i> Verifikasi Data
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>

<!-- AdminLTE JS -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
