<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\TransactionMessage;
use App\Models\TransactionReview;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    public function getTransaction($item_id)
    {
        $user = Auth::user();
        $item = Item::with('order.messages.user')->findOrFail($item_id);
        $order = $item->order;
        $messages = TransactionMessage::where('order_id', $order->id)->with('user')->get();
        $completed = $order->status === 'complete';

        if ($user->id === $item->user_id){
            $partner = User::findOrFail($order->user_id);
        } else {
            $partner = User::findOrFail($item->user_id);
        }

        $purchasedItems = Item::whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereDoesntHave('order.reviews', function ($query) use ($user) {
                $query->where('reviewer_id', $user->id);
            })
            ->where('id', '!=', $item->id)
            ->with('order.messages')
            ->get();

        $soldItems = Item::where('user_id', $user->id)
            ->has('order')
            ->whereDoesntHave('order.reviews', function ($query) use ($user) {
                $query->where('reviewer_id', $user->id);
            })
            ->where('id', '!=', $item->id)
            ->with('order.messages')
            ->get();

        $allTransactions = $purchasedItems->merge($soldItems);

        $allTransactions = $allTransactions->sortByDesc(function ($item) {
            return optional($item->order->messages->last())->created_at;
        })->values();

        $reviewExists = TransactionReview::where('order_id', $order->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        TransactionMessage::where('order_id', $order->id)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('transaction', compact(
            'user', 'item', 'partner', 'messages', 'order', 'reviewExists', 'completed', 'allTransactions'
        ));
    }

    public function store(TransactionRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::with('order')->findOrFail($item_id);
        $partner = User::findOrFail($item->user_id);
        $imagePath = $request->file('image')
            ? $request->file('image')->store('images', 'public')
            : null;
        $message = TransactionMessage::create([
            'order_id' => $item->order->id,
            'user_id'  => $user->id,
            'message'  => $request->message,
            'image'    => $imagePath,
        ]);

        return redirect()->back()->with('message_sent', true);
    }

    public function update(Request $request, $message_id)
    {
        $message = TransactionMessage::findOrFail($message_id);
        $message->update([
            'message' => $request->message,
        ]);

        return redirect()->back();
    }

    public function destroy($message_id)
    {
        $message = TransactionMessage::findOrFail($message_id);

        $message->delete();

        return redirect()->back();
    }
}
