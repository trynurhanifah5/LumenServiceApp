<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::orderBy("id", "DESC")->paginate(2)->toArray();

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
}