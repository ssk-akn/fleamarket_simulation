<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'brand',
        'price',
        'description',
        'image'
    ];

    public function scopeItemsSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedByUser()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

}
