<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostImageController extends Controller
{
    private function storeImage(Request $request)
    {
        $newImageName = uniqid().'-'.$request->image->getClientOriginalName();
        $request->image->move(public_path('images'), $newImageName);

        return '/images/'.$newImageName;
    }

    public function store(Request $request)
    {
        if (! Auth::User()) {
            abort(404);
        }

        return response()->json([
            'url' => $this->storeImage($request),
        ]);
    }
}
