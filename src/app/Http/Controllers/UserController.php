<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Order;

class UserController extends Controller
{
    public function getMypage(Request $request)
    {
        $user = Auth::user();

        $sellItems = Item::where('user_id', $user->id)->get();

        $buyItemIds = Order::where('user_id', $user->id)->pluck('item_id');
        $buyItems = Item::whereIn('id', $buyItemIds)->get();

        $purchasedItems = Item::whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereDoesntHave('order.reviews', function ($query) use ($user) {
                $query->where('reviewer_id', $user->id);
            })
            ->with('order.messages')
            ->get();

        $soldItems = Item::where('user_id', $user->id)
            ->has('order')
            ->whereDoesntHave('order.reviews', function ($query) use ($user) {
                $query->where('reviewer_id', $user->id);
            })
            ->with('order.messages')
            ->get();

        $allTransactions = $purchasedItems->merge($soldItems);

        $allTransactions = $allTransactions->sortByDesc(function ($item) {
            return optional($item->order->messages->last())->created_at;
        })->values();

        $page = $request->get('page', 'sell', 'trading');

        $unreadCounts = [];

        foreach ($allTransactions as $item) {
            $order = $item->order;
            $count = $order
                ? $order->messages()
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count()
                : 0;
            $unreadCounts[$item->id] = $count;
        }

        $totalUnread = array_sum($unreadCounts);

        return view('mypage', compact('user', 'sellItems', 'buyItems', 'allTransactions', 'page', 'unreadCounts', 'totalUnread'));
    }

    public function getProfile()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::delete('public/' . $user->image);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $user->image = $imagePath;
        }

        $user->name = $request->name;
        $user->postcode = $request->postcode;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect('/mypage');
    }
}
