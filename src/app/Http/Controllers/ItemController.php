<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = Auth::id();
        $page = $request->query('page', 'all');
        $keyword = $request->query('keyword');

        $items = $this->getFilteredItems($page, $keyword, $currentUserId);

        return view('index', compact('items', 'page', 'keyword'));
    }

    private function getFilteredItems($page, $keyword, $currentUserId)
    {
        if ($page === 'mylist' && !$currentUserId) {
            return collect([]);
        }

        $itemQuery = Item::with('order');

        if ($page === 'mylist' && $currentUserId) {
            $itemQuery = Auth::user()->likedItems()->with('order');
        } else {
            if ($currentUserId) {
                $itemQuery->where('user_id', '!=', $currentUserId);
            }
        }

        if ($keyword) {
            $itemQuery->itemsSearch($keyword);
        }

        return $itemQuery->get();
    }

    public function detail($item_id)
    {
        $item = Item::with(['categories', 'condition', 'likedByUser', 'comments.user'])
        ->findOrFail($item_id);

        $likeCount = $item->likedByUser->count();
        $isLiked = $item->likedByUser->contains(Auth::id());
        $comments = $item->comments;

        return view('item', compact(['item', 'likeCount', 'isLiked', 'comments']));
    }
}