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
        $acceptHeader = $request->header('Accept');

        // validasi: hanya application/json atau application/xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $posts = Post::OrderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json') {
                // response json
                return response()->json($posts->items('data'), 200);
            } else {
                // create xml posts element
                $xml = new \SimpleXMLElement('<posts/>');
                foreach ($posts->items('data') as $item) {
                    // create xml posts element
                    $xmlItem = $xml->addChild('post');

                    // mengubah setiap field post menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('title', $item->title);
                    $xmlItem->addChild('status', $item->status);
                    $xmlItem->addChild('content', $item->content);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return $xml->asXML();
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }
}