<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>
        @if($user->role == 'tu')
            Dashboard TU
        @elseif($user->role == 'kanit')
            Dashboard Kanit
        @else
            Dashboard
        @endif
    </title>

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
            <span class="brand-text font-weight-light">
                @if($user->role == 'tu')
                    Aplikasi TU
                @elseif($user->role == 'kanit')
                    Aplikasi Kanit
                @else
                    Aplikasi Dashboard
                @endif
            </span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                    @php
                        if ($user->role == 'tu') {
                            $menus = [
                                ['url' => '/kalender', 'icon' => 'fas fa-calendar', 'label' => 'Kelola Kalender Akademik'],
                                ['url' => '/inventaris', 'icon' => 'fas fa-boxes', 'label' => 'Kelola Inventaris'],
                                ['url' => '/rekapan', 'icon' => 'fas fa-clipboard-list', 'label' => 'Rekapan'],
                            ];
                        } elseif ($user->role == 'kanit') {
                            $menus = [
                                ['url' => '/rekapan', 'icon' => 'fas fa-clipboard-list', 'label' => 'Lihat Rekapan'],
                                ['url' => '/laporan', 'icon' => 'fas fa-file-alt', 'label' => 'Laporan Unit'],
                            ];
                        } else {
                            $menus = [];
                        }
                    @endphp

                    @foreach($menus as $menu)
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
            <h1>
                @if($user->role == 'tu')
                    Dashboard TU
                @elseif($user->role == 'kanit')
                    Dashboard Kanit
                @else
                    Dashboard
                @endif
            </h1>
        </section>

        <section class="content px-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header 
                            @if($user->role == 'tu') bg-warning 
                            @elseif($user->role == 'kanit') bg-primary 
                            @else bg-secondary @endif
                            text-white">
                            <h3 class="card-title">
                                Selamat datang, {{ $user->username }}
                            </h3>
                        </div>

                        <div class="card-body">
                            <p><b>Role Anda:</b> {{ ucfirst($user->role) }}</p>
                            <p><b>Unit Anda:</b> {{ $user->unit->nama_unit ?? '-' }}</p>

                            <!-- Tombol menu -->
                            @foreach($menus as $menu)
                                <a href="{{ url($menu['url']) }}" class="btn btn-outline-dark mb-2">
                                    <i class="{{ $menu['icon'] }}"></i> {{ $menu['label'] }}
                                </a>
                            @endforeach
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
