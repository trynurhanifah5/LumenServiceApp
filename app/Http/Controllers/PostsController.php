<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    // 1. Menampilkan semua data (Query: all)
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            "message" => "success",
            "results" => $posts
        ], 200);
    }

    // 2. Menyimpan data baru (Query: create)
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'   => 'required',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title'   => $request->input('title'),
            'content' => $request->input('content')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post Berhasil Disimpan!',
            'data'    => $post
        ], 201);
    }

    // 3. Menampilkan detail data berdasarkan ID (Query: find /findOrFail)
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Post',
            'data'    => $post
        ], 200);
    }

    // 4. Mengubah / Update data (Query: update)
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $post->update([
            'title'   => $request->input('title'),
            'content' => $request->input('content')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post Berhasil Diperbarui!',
            'data'    => $post
        ], 200);
    }

    // 5. Menghapus data (Query: delete)
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post Berhasil Dihapus!'
        ], 200);
    }
}