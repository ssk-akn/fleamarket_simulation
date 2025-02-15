<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function getPurchase($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $payment = session('payment', null);

        return view('purchase', compact(['item', 'user', 'payment']));
    }

    public function updatePayment(Request $request)
    {
        session(['payment' => $request->payment]);

        return redirect('/purchase/{item_id}', ['item_id' => $request->item_id]);
    }

    public function getAddress($item_id)
    {
        $user = Auth::user();
        return view('address', compact('user', 'item_id'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        session([
            'new_postcode' => $request->postcode,
            'new_address' => $request->address,
            'new_building' => $request->building ?? null,
        ]);

        return redirect('/purchase/{item_id}', compact('item_id'));
    }

    public function store(PurchaseRequest $request)
    {
        $item = Item::find($request->item_id);

        Order::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'payment' => $request->payment,
            'postcode' => session('new_postcode', Auth::user()->postcode),
            'address' => session('new_address', Auth::user()->address),
            'building' => session('new_building', Auth::user()->building) ?? null,
        ]);
        return redirect('/mypage');
    }

}
