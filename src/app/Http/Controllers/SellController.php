<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class SellController extends Controller
{
    public function getSell()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $userId = Auth::id();

        $item = new Item();
        $item->user_id = $userId;
        $item->condition_id = $request->condition_id;
        $item->name = $request->name;
        $item->brand = $request->brand;
        $item->price = $request->price;
        $item->description = $request->description;
        $imagePath = $request->file('image')->store('images', 'public');
        $item->image = $imagePath;
        $item->save();

        $item->categories()->sync($request->categories);

        return redirect('/mypage');
    }
}
