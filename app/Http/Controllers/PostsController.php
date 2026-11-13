<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil data dengan paginate
        $posts = Post::orderBy("id", "DESC")->paginate(10);

        // Ubah struktur item di dalam data agar urutan key-nya sesuai dengan referensi
        $posts->getCollection()->transform(function ($post) {
            return [
                "id"         => $post->id,
                "title"      => $post->title,
                "status"     => $post->status,
                "content"    => $post->content,
                "user_id"    => $post->user_id,
                "created_at" => $post->created_at,
                "updated_at" => $post->updated_at,
            ];
        });

        return response()->json($posts, 200);
    }
}