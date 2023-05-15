<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('is_published', true)->orderBy('id', 'desc')->get();

        return view('welcome', [
            'posts' => $posts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        $nextPost = Post::where('id', '>', $id)->min('id');

        $user = User::find($post->user_id);

        if ($post->is_published == false) {
            if (Auth::User()) {
                if (Auth::User() == $user || Auth::User()->hasRole('Admin')) {
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }

        return view('post.show', [
            'post' => $post,
            'nextPost' => $nextPost,
        ]);
    }
}
