<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapan Unit {{ $unit->nama_unit }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
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
        <a href="{{ url('/dashboard') }}" class="brand-link"><span class="brand-text font-weight-light">Aplikasi</span></a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item"><a href="{{ url('/dashboard') }}" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li class="nav-item"><a href="{{ url('/rekapan') }}" class="nav-link active"><i class="nav-icon fas fa-clipboard-list"></i> Rekapan</a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header px-3 py-2">
            <h1>Rekapan Unit: ID {{ $unit->id }} – {{ $unit->nama_unit }}</h1>
        </section>

        <section class="content px-3">
            <div class="card">
                <div class="card-body">

                    <!-- Filter bulan/tahun -->
                    <form method="GET" action="{{ route('rekapan.show', $unit->id) }}" class="row g-2 mb-3">
                        <div class="col-auto">
                            <select name="bulan" class="form-select">
                                @foreach(range(1,12) as $b)
                                    <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="tahun" class="form-select">
                                @foreach(range(date('Y')-5, date('Y')) as $t)
                                    <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                    <!-- Totals -->
                    <p><b>Total Presensi:</b> {{ $total_presensi }}</p>
                    <p><b>Total Ijin:</b> {{ $total_ijin }}</p>

                    <hr>

                    <!-- Tabel Presensi -->
                    <h4>Data Presensi</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Datang</th>
                                <th>Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensi as $index => $p)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $p->user->username ?? '-' }}</td>
                                    <td>{{ $p->tanggal }}</td>
                                    <td>{{ $p->jenis_presensi }}</td>
                                    <td>{{ $p->status === 'datang' ? $p->waktu : '-' }}</td>
                                    <td>{{ $p->status === 'pulang' ? $p->waktu : '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">Belum ada data presensi</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <hr>

                    <!-- Tabel Ijin -->
                    <h4>Data Ijin</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ijin as $index => $i)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $i->user->username ?? '-' }}</td>
                                    <td>{{ $i->tanggal }}</td>
                                    <td>{{ $i->keterangan }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Belum ada data ijin</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <a href="{{ url('/rekapan') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Unit</a>

                </div>
            </div>
        </section>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
