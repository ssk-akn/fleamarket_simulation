<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($item_id)
    {
        $user = Auth::user();

        if (!$user->likedItems()->where('item_id', $item_id)->exists()) {
            $user->likedItems()->attach($item_id);
        }

        return redirect()->route('item.detail', ['item_id' => $item_id]);
    }

    public function destroy($item_id)
    {
        $user = Auth::user();

        if ($user->likedItems()->where('item_id', $item_id)->exists()) {
            $user->likedItems()->detach($item_id);
        }

        return redirect()->route('item.detail', ['item_id' => $item_id]);
    }
}
