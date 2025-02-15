<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $user = Auth::user();

        Comment::create([
            'item_id' => $item_id,
            'user_id' => $user_id,
            'comment' => $request->comment,
        ]);
        return redirect('/item/{item_id}/', compact('item_id'));
    }
}
