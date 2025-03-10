<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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




    public function createCheckoutSession(PurchaseRequest $request)
    {
        $user = Auth::user();
        $item = Item::findOrFail($request->item_id);

        $paymentMethod = $request->payment;

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentType = $paymentMethod === 'カード支払い' ? 'card' : 'konbini';

        $session = Session::create([
            'payment_method_types' => [$paymentType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost/purchase/success' . '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect($session->url);
    }

    public function checkoutSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($request->query('session_id'));

        $userId = $session->metadata->user_id;
        $itemId = $session->metadata->item_id;
        $postcode = $session->metadata->postcode;
        $address = $session->metadata->address;
        $building = $session->metadata->building;
        $paymentMethod = $session->payment_method_types[0];

        Order::create([
            'item_id' => $itemId,
            'user_id' => $userId,
            'payment' => $paymentMethod,
            'postcode' => $postcode,
            'address' => $address,
            'building' => $building,
        ]);

        return redirect('/mypage');
    }
}