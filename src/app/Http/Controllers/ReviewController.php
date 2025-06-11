<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\TransactionReview;
use App\Mail\TradeCompleteNotification;

class ReviewController extends Controller
{
    public function complete($order_id)
    {
        $order = Order::with('item.user')->findOrFail($order_id);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->status = 'complete';
        $order->save();

        $email = $order->item->user->email;
        Mail::to($email)->send(new TradeCompleteNotification(Auth::user(), $order->item));

        return redirect()->route('transaction.get', ['item_id' => $order->item_id])
                        ->with('show_review_modal', true);
    }

    public function review(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        TransactionReview::create([
            'order_id' => $order_id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $request->partner_id,
            'rating' => $request->rating,
        ]);

        return redirect()->route('item.list');
    }
}
