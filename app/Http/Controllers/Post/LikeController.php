<?php

namespace App\Http\Controllers\Post;

use Auth;
use Session;
use App\Models\Post;
use App\Models\Post\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function like(Request $request, $post_id)
    {
        // If the post IDs do not match, the user is up to some shenanigans
        if ($post_id != $request->post_id) {
            if ($request->ajax()) {
                return response(['status' => 'The post IDs do not match.']);
            } else {
                Session::flash('error', 'The post IDs do not match.');
                return redirect()->back();
            }
        }

        $like = new Like;
        $like->user_id = $request->user_id;
        $like->post_id = $request->post_id;
        $like->save();

        $post = Post::where('id', $post_id)
            ->first();

        $post->like_count = $post->like_count + 1;
        $post->save();

        if ($request->ajax()) {
            return response(['status' => 'The post was liked.']);
        } else {
            Session::flash('success', 'The post was liked.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
