<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Order;

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
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $likeItem = Item::create([
            'name' => 'Like Item',
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
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 123,
            'description' => 'example',
            'image' => 'dummy2.png'
        ]);

        $user1->markEmailAsVerified();
        $user1->likedItems()->attach($likeItem->id);

        $response = $this->actingAs($user1)->get('/?page=mylist');

        $response->assertStatus(200);
        $response->assertSee('Like Item');
        $response->assertDontSee('Unlike Item');
    }

    /** @test */
    public function purchased_items_are_displayed_as_sold()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item = Item::create([
            'name' => 'Purchased Item',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 123,
            'description' => 'example',
            'image' => 'dummy.png'
        ]);

        Order::create([
            'user_id' => $user1->id,
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postcode' => 1234567,
            'address' => 'example'
        ]);

        $user1->markEmailAsVerified();

        $response = $this->actingAs($user1)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function hidden_own_items_on_my_list()
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item = Item::create([
            'name' => 'My Item',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 123,
            'description' => 'example',
            'image' => 'dummy.png'
        ]);

        $user->markEmailAsVerified();
        $user->likedItems()->attach($item->id);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('My Item');
    }

    /** @test */
    public function guest_sees_no_items()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item = Item::create([
            'name' => 'Item',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 123,
            'description' => 'example',
            'image' => 'dummy1.png'
        ]);

        $user1->markEmailAsVerified();

        $user1->likedItems()->attach($item->id);

        $response = $this->get('/?page=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('Item');
    }
}
