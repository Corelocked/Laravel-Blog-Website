<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostUpdateFormRequest;
use App\Models\HistoryPost;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:post-list', ['only' => ['index']]);
        $this->middleware('permission:post-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:post-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
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
                $posts = Post::where('user_id', $request->user)->orderBy('id', $order)->paginate($limit);
            } else {
                $posts = Post::orderBy('id', $order)->paginate($limit);
            }
        } else {
            $posts = Post::orderBy('id', $order)->where('user_id', Auth::User()->id)->paginate($limit);
        }

        $users = User::all();

        return view('post.index', [
            'posts' => $posts,
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
    public function create(Request $request)
    {
        $saved = SavedPost::where('user_id', Auth::User()->id)->get();

        if (count($saved) > 0 && ! $request->new && ! $request->edit) {
            return redirect()->route('posts.saved');
        }

        if ($request->edit) {
            $saved = SavedPost::find($request->edit);

            if ($saved->user_id != Auth::User()->id) {
                abort(404);
            }

            return view('post.create', [
                'post' => $saved,
            ]);
        }

        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $SavedPost = SavedPost::find($request->id_saved_post);

        $this->checkUserIdPost(null, $SavedPost);

        $validation = [
            'title' => 'required|max:255|unique:posts,title',
            'excerpt' => 'required|max:510',
            'body' => 'required',
        ];

        if (! isset($request->image)) {
            if (isset($SavedPost->image_path)) {
                $request['image_path'] = $SavedPost->image_path;
                $request->except('image');

                $validation += ['image_path' => 'required'];
            } else {
                $validation += ['image' => 'required|mimes:png,jpg,jpeg|max:10248'];
            }
        } else {
            $validation += ['image' => 'required|mimes:png,jpg,jpeg|max:10248'];
        }

        $request->validate(
            $validation
        );

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'image_path' => isset($request->image_path) ? $request->image_path : $this->storeImage($request),
            'is_published' => $request->is_published == 'on' ? true : false,
        ]);

        if ($SavedPost) {
            $SavedPost->delete();
        }

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        $this->checkUserIdPost($post);

        return view('post.edit', [
            'post' => $post,
            'editPost' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateFormRequest $request, $id)
    {
        $request->validated();

        $post = Post::where('id', $id);

        $this->checkUserIdPost($post->get()[0]);

        HistoryPost::create([
            'post_id' => $post->get()[0]->id,
            'title' => $post->get()[0]->title,
            'excerpt' => $post->get()[0]->excerpt,
            'body' => $post->get()[0]->body,
            'image_path' => $post->get()[0]->image_path,
            'is_published' => $post->get()[0]->is_published,
            'additional_info' => $post->get()[0]->additional_info,
        ]);

        $input['title'] = $request->title;
        $input['excerpt'] = $request->excerpt;
        $input['body'] = $request->body;
        $input['is_published'] = $request->is_published == 'on' ? true : false;
        $input['additional_info'] = 0;

        if ($request->image) {
            $input['image_path'] = $this->storeImage($request);
        }

        $post->update($input);

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        $this->checkUserIdPost($post);

        $post->delete();

        return redirect()->route('posts.index');
    }

    private function storeImage($request)
    {
        $newImageName = uniqid().'-'.$request->image->getClientOriginalName();
        $request->image->move(public_path('images'), $newImageName);

        return '/images/'.$newImageName;
    }

    private function checkUserIdPost(Post $post = null, SavedPost $savedPost = null): void
    {
        if ($post) {
            if ($post->user_id != Auth::id() && ! Auth::User()->hasRole('Admin')) {
                abort(403);
            }
        }
        if ($savedPost) {
            if ($savedPost->user_id != Auth::id() && ! Auth::User()->hasRole('Admin')) {
                abort(403);
            }
        }
    }
}
