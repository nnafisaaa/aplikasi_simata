<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Unit Rekapan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

    <h2 class="mb-4">Daftar Unit</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Unit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $index => $unit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $unit->nama_unit }}</td>
                <td>
                    <a href="{{ route('rekapan.show', $unit->id) }}" class="btn btn-primary btn-sm">
                        Lihat Rekapan
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Belum ada data unit</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
