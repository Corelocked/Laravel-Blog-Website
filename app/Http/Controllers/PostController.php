<?php

namespace App\Http\Controllers;

use App\Models\HighlightPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $posts = Post::with('category')
            ->where('is_published', true)
            ->select('posts.*', \DB::raw('(SELECT COUNT(*) FROM highlight_posts WHERE post_id = posts.id) > 0 AS is_highlighted'))
            ->orderBy('id', 'desc')->get();

        $highlighted_posts = HighlightPost::all();

        return view('welcome', [
            'posts' => $posts,
            'highlighted_posts' => $highlighted_posts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return Factory|View
     */
    public function show(string $slug)
    {
        // $post = Post::findOrFail($id);
        $post = Post::where('slug', $slug)->firstOrFail();

        $nextPost = Post::where('id', '>', $post->id)->first();

        $user = User::find($post->user_id);

        if (!$post->is_published) {
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
