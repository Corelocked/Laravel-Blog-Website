<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:comment-list', ['only' => ['index']]);
        $this->middleware('permission:comment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:comment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->order)) {
            $order = $request->order;
        } else {
            $order = 'desc';
        }
        if (isset($request->limit)) {
            $limit = $request->limit;
        } else {
            $limit = 20;
        }
        if (isset($request->user) && $request->user != 0) {
            $user = $request->user;
        } else {
            $user = 0;
        }

        if (Auth::User()->hasRole('Admin')) {
            if ($user != 0) {
                $comments = Comment::join('posts', 'posts.id', '=', 'comments.post_id')->where('posts.user_id', $user)->orderBy('comments.id', $order)->paginate($limit, ['comments.id', 'comments.post_id', 'comments.name', 'comments.body', 'comments.created_at']);
            } else {
                $comments = Comment::orderBy('id', $order)->paginate($limit);
            }
        } else {
            $comments = Comment::join('posts', 'posts.id', '=', 'comments.post_id')->where('posts.user_id', Auth::id())->orderBy('id', $order)->paginate($limit, ['comments.id', 'comments.post_id', 'comments.name', 'comments.body', 'comments.created_at']);
        }

        $users = User::all();

        return view('comment.index', [
            'comments' => $comments,
            'users' => $users,
            'order' => $order,
            'limit' => $limit,
            'selectedUser' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path = parse_url($request->headers->get('referer'), PHP_URL_PATH);
        $post_id = explode('/', $path)[2];

        $request->validate([
            'name' => 'required',
            'body' => 'required',
        ]);

        $comment = Comment::create([
            'name' => $request->name,
            'body' => $request->body,
            'post_id' => $post_id,
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::find($id);
        $post = Post::find($comment->post_id);

        $this->checkUserIdPost($post);

        return view('comment.edit', [
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'body' => 'required',
        ]);

        $input = $request->all();

        $path = parse_url($request->headers->get('referer'), PHP_URL_PATH);
        $comment_id = explode('/', $path)[3];

        if ($id != $comment_id) {
            abort(403);
        }

        $comment = Comment::find($comment_id);

        $post = Post::find($comment->post_id);

        $this->checkUserIdPost($post);

        $comment->update($input);

        return redirect()->route('comments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $post = Post::find($comment->post_id);

        $this->checkUserIdPost($post);

        $comment->delete();

        return redirect()->back();
    }

    private function checkUserIdPost(Post $Post): void
    {
        if ($Post->user_id != Auth::id() && ! Auth::User()->hasRole('Admin')) {
            abort(403);
        }
    }
}
