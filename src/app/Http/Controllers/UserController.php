<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $page = $request->get('page', 'sell');

        return view('mypage', compact('user', 'sellItems', 'buyItems'. 'page'));
    }

    public function getProfile()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function update(Request $request)
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

        return redirect('/mypage/profile');
    }
}
