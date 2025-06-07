<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\TransactionMessage;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    public function getTransaction($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $partner = User::findOrFail($item->user_id);
        $order = Order::where('item_id', $item_id)->first();
        $messages = TransactionMessage::where('order_id', $order->id)->with('user')->get();
        return view('transaction', compact('user', 'item', 'partner', 'messages'));
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

        return redirect()->back();
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
