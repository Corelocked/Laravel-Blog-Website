<?php

namespace App\Http\Controllers;

use App\Models\SavedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostSavedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saved = SavedPost::where('user_id', Auth::User()->id)->orderBy('id', 'DESC')->get();

        return view('post.saved', [
            'posts' => $saved,
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
        $post = SavedPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'image_path' => $request->image != 'undefined' ? $this->storeImage($request) : null,
            'is_published' => $request->is_published ? 1 : 0,
        ]);

        return response()->json(['message' => 'Zapisano!', 'id' => $post->id]);
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
        $saved = SavedPost::findOrFail($id);

        $this->checkUserIdPost($saved);

        return redirect('dashboard/posts/create?edit='.$saved->id);
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
        $SavedPost = SavedPost::where('id', $id);

        if ($SavedPost->get()->isEmpty()) {
            abort(404);
        }

        $this->checkUserIdPost($SavedPost->get()[0]);

        $input['title'] = $request->title;
        $input['excerpt'] = $request->excerpt;
        $input['body'] = $request->body;
        $input['is_published'] = $request->is_published ? 1 : 0;

        if ($request->image != 'undefined') {
            $input['image_path'] = $this->storeImage($request);
        }

        $SavedPost->update($input);

        return response()->json(['message' => 'zapisano']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $SavedPost = SavedPost::findOrFail($id);

        $this->checkUserIdPost($SavedPost);

        $SavedPost->delete();

        return redirect()->back();
    }

    private function storeImage($request)
    {
        $newImageName = uniqid().'-'.$request->image->getClientOriginalName();
        $request->image->move(public_path('images'), $newImageName);

        return '/images/'.$newImageName;
    }

    private function checkUserIdPost(SavedPost $SavedPost): void
    {
        if ($SavedPost->user_id != Auth::id() && ! Auth::User()->hasRole('Admin')) {
            abort(403);
        }
    }
}
