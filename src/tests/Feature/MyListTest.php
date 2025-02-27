<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class MyListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function only_items_liked_by_user_display()
    {
        $user1 = User::cerate([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password')
        ]);
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password')
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ])

        $likeItem = Item::create([
            'name' 'Like Item',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 123,
            'description' => 'example',
            'image' => 'dummy1.png'
        ]);

        $unlikeItem = Item::create([
            'name' => 'Unlike Item',
            'user_id' => $user2->id,
            'condition' => $condition->id,
            'brand' => 'none',
            'price' => 123,
            'description' => 'example',
            'image' => 'dummy2.png'
        ]);
    }
}
