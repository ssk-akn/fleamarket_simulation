<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;

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
        $item_id = $request->item_id;

        return redirect()->route('purchase.get', ['item_id' => $item_id]);
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

        return redirect()->route('purchase.get', ['item_id' => $item_id]);
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
