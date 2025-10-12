<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Video YouTube</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar Video YouTube</h2>
        <a href="{{ route('videos.create') }}" class="btn btn-primary">+ Tambah Video</a>
    </div>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('videos.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari video..." value="{{ request('q') }}">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
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
                        <a href="{{ $video->youtube_url }}" target="_blank" class="btn btn-sm btn-danger">Lihat di YouTube</a>
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

</body>
</html>
