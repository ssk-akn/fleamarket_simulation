<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TradeCompleteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $buyer;
    public $item;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $buyer, $item)
    {
        $this->buyer = $buyer;
        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('取引が完了しました')
                    ->view('emails.trade_complete')
                    ->with([
                        'buyerName' => $this->buyer->name,
                        'itemName' => $this->item->name,
                        'itemId' => $this->item->id,
                    ]);
    }
}
