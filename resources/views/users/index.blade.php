<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

    <h2 class="mb-4">Daftar User</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol tambah user baru --}}
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Tambah User Baru</a>

    {{-- Tabel user --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Username</th>
                <th>IMEI</th>
                <th>Role</th>
                <th>Unit</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->imei ?? '-' }}</td>
                    <td>{{ $user->role }}</td>

                    {{-- Tampilkan nama unit kalau ada relasinya, kalau tidak tampilkan ID --}}
                    <td>
                        @if($user->unit)
                            {{ $user->unit->nama_unit }}
                        @elseif($user->unit_id)
                            (ID: {{ $user->unit_id }})
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada user terdaftar</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
