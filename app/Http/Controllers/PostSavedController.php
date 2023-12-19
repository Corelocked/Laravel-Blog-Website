<?php

namespace App\Http\Controllers;

use App\Models\SavedPost;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class PostSavedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $saved = SavedPost::where('user_id', Auth::User()->id)->orderBy('id', 'DESC')->get();

        return view('post.saved', [
            'posts' => $saved,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
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
            'category_id' => $request->category_id ? $request->category_id : null,
        ]);

        return response()->json(['message' => 'Zapisano!', 'id' => $post->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return RedirectResponse|Redirector
     */
    public function edit(int $id)
    {
        $saved = SavedPost::findOrFail($id);

        $this->checkUserIdPost($saved);

        return redirect('dashboard/posts/create?edit='.$saved->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
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
        $input['category_id'] = $request->category_id;

        if ($request->image != 'undefined') {
            $input['image_path'] = $this->storeImage($request);
        }

        $SavedPost->update($input);

        return response()->json(['message' => 'zapisano']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $SavedPost = SavedPost::findOrFail($id);

        $this->checkUserIdPost($SavedPost);

        $SavedPost->delete();

        return redirect()->back();
    }

    private function storeImage(Request $request)
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
