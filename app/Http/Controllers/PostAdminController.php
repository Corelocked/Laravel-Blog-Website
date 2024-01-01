<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HighlightPost;
use App\Models\Post;
use App\Models\User;
use App\Models\SavedPost;
use App\Models\HistoryPost;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostUpdateFormRequest;
use Illuminate\Support\Facades\Schema;

class PostAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:post-list', ['only' => ['index']]);
        $this->middleware('permission:post-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:post-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }

    private function calculateReadTime($body)
    {
        $readingSpeed = 200;
        $words = str_word_count(strip_tags($body));
        return ceil($words / $readingSpeed);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        if ($request->input('order') !== null) {
            $order = $request->input('order');
        } else {
            $order = 'desc';
        }
        if ($request->input('limit') !== null) {
            $limit = $request->input('limit');
        } else {
            $limit = 20;
        }

        $posts = Post::with('category')
            ->select('posts.*', \DB::raw('(SELECT COUNT(*) FROM highlight_posts WHERE post_id = posts.id) > 0 AS is_highlighted'))
            ->orderBy('id', $order);
        if (Auth::User()->hasRole('Admin')) {
            if ($request->input('users') !== null && $request->input('users')[0] !== null) {
                if (isset($request->input('users')[1])) {
                    $temp = $request->input('users');
                } else {
                    $temp = explode(',', $request->input('users')[0]);
                }
                $posts->whereIn('user_id', $temp);
                $selected_users = User::whereIn('id', $temp)->withCount('posts')->get()->toArray();
                $selected_users_array = $temp;
            } else {
                $selected_users = null;
                $selected_users_array = null;
            }
        } else {
            $posts = Post::orderBy('id', $order)->where('user_id', Auth::Id());
            $selected_users = null;
            $selected_users_array = null;
        }

        if ($request->input('categories') !== null && $request->input('categories')[0] !== null) {
            if (is_array($request->input('categories'))) {
                $temp = explode(',', $request->input('categories')[0]);
            } else {
                $temp = explode(',', $request->input('categories'));
            }
            $posts->whereIn('category_id', $temp);
            if (Auth::user()->hasRole('Admin')) {
                $selected_categories = Category::whereIn('id', $temp)->withCount('posts')->get()->toArray();
            } else {
                $selected_categories = Category::whereIn('id', $temp)
                    ->withCount(['posts' => function ($query) {
                        $query->where('user_id', Auth::id());
                    }])
                    ->get()
                    ->toArray();
            }
            $selected_categories_array = $temp;
        } else {
            $selected_categories = null;
            $selected_categories_array = null;
        }

        if ($request->input('highlight') !== null && $request->input('highlight')[0] !== null) {
            $highlight = explode(',', $request->input('highlight')[0]);
            if ($highlight[0] and $highlight[1]) {
            } else {
                if ($highlight[0]) {
                    $posts->whereHas('highlightPosts');
                }
                if ($highlight[1]) {
                    $posts->doesntHave('highlightPosts');
                }
            }
        } else {
            $highlight = null;
        }

        $users = User::withCount('posts')->get();

        if (Auth::User()->hasRole('Admin')) {
            $categories = Category::withCount('posts')->get();
        } else {
            $categories = Category::withCount(['posts' => function ($query) {
                $query->where('user_id', Auth::id());
            }])->get();
        }

        if (Schema::hasColumn('posts', 'highlight_posts')) {
            $posts = $posts->where('highlight_posts', '=', true);
        }

        if (Auth::User()->hasRole('Admin')) {
            $countPosts = Post::all()->count();
        } else {
            $countPosts = Auth::user()->posts()->count();
        }

        if ((int)$limit === 0) {
            $posts = $posts->get();
        } else {
            $posts = $posts->paginate($limit);
        }

        $countHighlighted = HighlightPost::all()->count();

        return view('post.index', [
            'posts' => $posts,
            'users' => $users,
            'order' => $order,
            'limit' => $limit,
            'categories' => $categories,
            'selected_categories' => $selected_categories,
            'selected_categories_array' => $selected_categories_array,
            'selected_users' => $selected_users,
            'selected_users_array' => $selected_users_array,
            'countHighlighted' => $countHighlighted,
            'highlight' => $highlight,
            'countPosts' => $countPosts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse|Factory|View
     */
    public function create(Request $request)
    {
        $saved = SavedPost::where('user_id', Auth::User()->id)->get();
        $categories = Category::all();

        if (count($saved) > 0 && ! $request->new && ! $request->edit) {
            return redirect()->route('posts.saved');
        }

        if ($request->edit) {
            $saved = SavedPost::findOrFail($request->edit);

            if ($saved->user_id != Auth::User()->id) {
                abort(404);
            }

            return view('post.create', [
                'post' => $saved,
                'categories' => $categories,
            ]);
        }

        return view('post.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $SavedPost = SavedPost::find($request->id_saved_post);

        $this->checkUserIdPost(null, $SavedPost);

        $validation = [
            'title' => 'required|max:255|unique:posts,title',
            'excerpt' => 'required|max:510',
            'body' => 'required',
            'category_id' => 'required',
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
            'slug' => Str::slug($request->title),
            'is_published' => $request->is_published == 'on' ? true : false,
            'category_id' => $request->category_id,
            'read_time' => $this->calculateReadTime($request->body),
        ]);

        if ($SavedPost) {
            $SavedPost->delete();
        }

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $post = Post::with('category')->findOrFail($id);

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function edit(int $id)
    {
        $post = Post::findOrFail($id);

        $categories = Category::all();

        $this->checkUserIdPost($post);

        return view('post.edit', [
            'post' => $post,
            'categories' => $categories,
            'editPost' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostUpdateFormRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(PostUpdateFormRequest $request, int $id)
    {
        $request->validated();

        $post = Post::where('id', $id);

        if ($post->get()->isEmpty()) {
            abort(404);
        }

        $this->checkUserIdPost($post->get()[0]);

        HistoryPost::create([
            'post_id' => $post->get()[0]->id,
            'title' => $post->get()[0]->title,
            'excerpt' => $post->get()[0]->excerpt,
            'body' => $post->get()[0]->body,
            'image_path' => $post->get()[0]->image_path,
            'slug' => $post->get()[0]->slug,
            'is_published' => $post->get()[0]->is_published,
            'additional_info' => $post->get()[0]->additional_info,
            'category_id' => $post->get()[0]->category_id,
            'read_time' => $post->get()[0]->read_time,
        ]);

        $input['title'] = $request->title;
        $input['excerpt'] = $request->excerpt;
        $input['body'] = $request->body;
        $input['slug'] = Str::slug($request->title);
        $input['is_published'] = $request->is_published == 'on' ? true : false;
        $input['additional_info'] = 0;
        $input['category_id'] = $request->category_id;
        $input['read_time'] = $this->calculateReadTime($request->body);

        if ($request->image) {
            $input['image_path'] = $this->storeImage($request);
        }

        $post->update($input);

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);

        $this->checkUserIdPost($post);

        $post->delete();

        return redirect()->route('posts.index');
    }

    /**
     * Highlight the specified resource from storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function highlight(Request $request)
    {
        if (! Auth::User()->hasRole('Admin')) {
            abort(403);
        }

        $post = Post::findOrFail($request->id);

        $countHighlighted = HighlightPost::all()->count();

        $highlighted_post = HighlightPost::where(['post_id' => $request->id])->get();

        $isHighlighted = !empty($highlighted_post[0]);

        if ($isHighlighted) {
            $highlighted_post[0]->delete();
        } else {
            if ($countHighlighted >= 3) {
                abort(403);
            }

            HighlightPost::create([
                'post_id' => $post->id,
            ]);
        }

        return redirect()->back();
    }

    public function calculate(Request $request)
    {
        $readingTime = $this->calculateReadTime($request->get('body'));

        return response()->json($readingTime);
    }

    private function storeImage(Request $request)
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
