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

    public function getFilteredItems($page, $keyword, $currentUserId)
    {
        $itemQuery = Item::with('order');

        if ($page === 'mylist' && $currentUserId) {
            $itemQuery = Auth::user()->likedItems()->with('order');
        } else {
            if ($currentUserId) {
                $itemQuery->where('user_id', '!=', $currentUserId);
            }
        }

        if ($keyword) {
            $itemQuery->itemSearch($keyword);
        }

        return $itemQuery->get();
    }


}