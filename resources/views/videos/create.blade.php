<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Video Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Tambah Video Baru</h2>
        <a href="{{ route('videos.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Form Tambah Video --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('videos.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Judul Video</label>
                    <input type="text" name="title" class="form-control" id="title" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="youtube_url" class="form-label">Link YouTube</label>
                    <input type="url" name="youtube_url" class="form-control" id="youtube_url" placeholder="https://www.youtube.com/watch?v=..." required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Video</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
