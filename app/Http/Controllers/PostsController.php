<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('read-post')) {
            return response()->json([
                'success' => false,
                'status'  => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        if (Auth::user()->role === 'admin') {
            $posts = Post::orderBy('id', 'ASC')->get();
        } else {
            $posts = Post::where('user_id', Auth::id())->orderBy('id', 'ASC')->get();
        }

        $response = [
            'total_count' => $posts->count(),
            'data'        => $posts
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('create-post')) {
            return response()->json([
                'success' => false,
                'status'  => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

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
        if (Gate::denies('read-post')) {
            return response()->json([
                'success' => false,
                'status'  => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        if (Auth::user()->role !== 'admin' && (int) $post->user_id !== (int) Auth::id()) {
            return response()->json([
                'success' => false,
                'status'  => 403,
                'message' => 'You are unauthorized'
            ], 403);
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
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        if (Gate::denies('update-post', $post)) {
            return response()->json([
                'success' => false,
                'status'  => 403,
                'message' => 'You are unauthorized'
            ], 403);
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
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        if (Gate::denies('delete-post', $post)) {
            return response()->json([
                'success' => false,
                'status'  => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post berhasil dihapus'], 200);
    }
}