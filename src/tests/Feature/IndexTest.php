<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Order;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function all_items_are_display_for_guest_user()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password')
        ]);
        $user2 = User::create([
            'name' => 'User two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password')
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item1 = Item::create([
            'name' => 'Item1',
            'user_id' => $user1->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy1.jpg'
        ]);
        $item2 = Item::create([
            'name' => 'Item2',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy2.jpg'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Item1');
        $response->assertSee('Item2');
    }

    /** @test */
    public function purchase_items_display_sold_label()
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password')
        ]);
        $condition = Condition::create([
            'condition' => 'good'
        ]);
        $item = Item::create([
            'name' => 'Purchase Item',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy.jpg'
        ]);

        Order::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'postcode' => 1234567,
            'address' => 'example',
            'payment' => 'カード支払い'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function logged_in_user_does_nat_see_their_own_items_in_all_items_view()
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password')
        ]);
        $condition = Condition::create([
            'condition' => 'good'
        ]);
        $myItem = Item::create([
            'name' => 'My Item',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy1.jpg'
        ]);

        $otherUser = User::create([
            'name' => 'User two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password')
        ]);
        $otherItem = Item::create([
            'name' => 'Other Item',
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy2.jpg'
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('My Item');
        $response->assertSee('Other Item');
    }
}
