<?php

namespace App\Http\Controllers;

use App\Models\HistoryPost;
use App\Models\Post;

class PostHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:post-list', ['only' => ['index']]);
        $this->middleware('permission:post-edit', ['only' => ['revert', 'update']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $historyPosts = HistoryPost::where('post_id', $id)->orderBy('id', 'DESC')->get();
        $actualPost = Post::findOrFail($id);

        return view('post.history', [
            'posts' => $historyPosts,
            'actualPost' => $actualPost,
            'id' => $id,
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
        $post = HistoryPost::with('category')->findOrFail($id);

        return response()->json($post);
    }

    public function revert($postid, $historyid)
    {
        $post = Post::findOrFail($postid);

        $historyPost = HistoryPost::findOrFail($historyid);

        HistoryPost::create([
            'post_id' => $post->id,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'body' => $post->body,
            'image_path' => $post->image_path,
            'slug' => $post->slug,
            'is_published' => $post->is_published,
            'additional_info' => $post->additional_info,
            'category_id' => $post->category_id,
        ]);

        $post->update([
            'title' => $historyPost->title,
            'excerpt' => $historyPost->excerpt,
            'body' => $historyPost->body,
            'is_published' => $historyPost->is_published,
            'image_path' => $historyPost->image_path,
            'slug' => $historyPost->slug,
            'additional_info' => 1,
            'category_id' => $historyPost->category_id,
        ]);

        return redirect()->route('posts.edit', ['post' => $postid]);
    }
}
