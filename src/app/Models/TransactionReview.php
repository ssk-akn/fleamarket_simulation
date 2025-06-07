<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order;

class TransactionReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
