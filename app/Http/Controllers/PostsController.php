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
        $posts = Post::Where(["user_id" => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();

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
     * Store a newly created resource in stroge.
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id'
        ];

        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = Post::create($input);
        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in stroge.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @retuen \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $post = Post::find($id);

        if(!$post) {
            abort(404);
        }

        // validation
        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id'
        ];

        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        // validation end

        $post->fill($input);
        $post->save();

        return response()->json($post, 200);
    }
}