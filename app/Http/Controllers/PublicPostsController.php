<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PublicPostsController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return response()->json([
            'total_count' => $posts->total(),
            'limit'       => $posts->perPage(),
            'pagination'  => [
                'next_page'    => $posts->nextPageUrl(),
                'current_page' => $posts->currentPage(),
            ],
            'data'        => $posts->items(),
        ], 200);
    }

    public function show($id)
    {
        $post = Post::where('id', $id)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        return response()->json(['data' => $post], 200);
    }
}