<?php
namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        // Ambil semua data video
        $videos = Video::all();

        // Tampilkan ke view admin/videos/index.blade.php
        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        // Form tambah video baru
        return view('videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
        ]);

        Video::create($validated);

        // Setelah simpan, kembali ke halaman daftar
        return redirect()->route('videos.index')->with('success', 'Video berhasil ditambahkan!');
    }

    public function show(Video $video)
    {
        // Tampilkan detail video
        return view('videos.show', compact('video'));
    }

    public function edit(Video $video)
    {
        // Form edit video
        return view('videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
        ]);

        $video->update($validated);

        return redirect()->route('videos.index')->with('success', 'Video berhasil diperbarui!');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('videos.index')->with('success', 'Video berhasil dihapus!');
    }
}
