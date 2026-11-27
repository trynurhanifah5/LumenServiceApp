<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::where("user_id", Auth::id())->orderBy("id", "DESC")->paginate(2)->toArray();

        $response = [
            "total_count"  => $posts["total"],
            "limit"        => $posts["per_page"],
            "pagination"   => [
                "next_page"    => $posts["next_page_url"],
                "current_page" => $posts["current_page"]
            ],
            "data"         => $posts["data"]
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'   => 'required|min:5',
            'content' => 'required|min:10',
            'status'  => 'required|in:draft,published',
        ]);

        $input = $request->all();
        // Otomatis assign user_id dari user yang sedang login via JWT token
        $input['user_id'] = Auth::id();

        $post = Post::create($input);
        
        return response()->json([
            'message' => 'Post berhasil ditambahkan',
            'data'    => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        return response()->json(['data' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        $this->validate($request, [
            'title'   => 'required|min:5',
            'content' => 'required|min:10',
            'status'  => 'required|in:draft,published',
        ]);

        $post->fill($request->all());
        $post->save();

        return response()->json([
            'message' => 'Post berhasil diperbarui',
            'data'    => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post berhasil dihapus'], 200);
    }
}