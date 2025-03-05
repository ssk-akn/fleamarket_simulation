<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $user = Auth::user();

        Comment::create([
            'item_id' => $item_id,
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);
        return redirect()->route('item.detail', compact('item_id'));
    }
}