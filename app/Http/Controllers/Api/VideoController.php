<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return response()->json([
            'status' => 'success',
            'data' => $videos
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
        ]);

        $video = Video::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Video berhasil ditambahkan!',
            'data' => $video
        ], 201);
    }

    public function show($id)
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json([
                'status' => 'error',
                'message' => 'Video tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $video
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json([
                'status' => 'error',
                'message' => 'Video tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
        ]);

        $video->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Video berhasil diperbarui!',
            'data' => $video
        ], 200);
    }

    public function destroy($id)
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json([
                'status' => 'error',
                'message' => 'Video tidak ditemukan'
            ], 404);
        }

        $video->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Video berhasil dihapus!'
        ], 200);
    }
}
