<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function user_can_purchase()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'postcode' => 1234567,
            'address' => '北海道札幌市'
        ]);
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password')
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item = Item::create([
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $this->actingAs($user1)->get('/purchase/' . $item->id);
        $this->actingAs($user1)->post('/purchase', [
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postcode' => $user1->postcode,
            'address' => $user1->address,
        ]);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user1->id,
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postcode' => $user1->postcode,
            'address' => $user1->address,
        ]);
    }

    /** @test */
    public function purchase_items_is_displayed_as_Sold()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'postcode' => 1234567,
            'address' => '北海道札幌市'
        ]);
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password')
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item = Item::create([
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $this->actingAs($user1)->get('/purchase/' . $item->id);
        $this->actingAs($user1)->post('/purchase', [
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postcode' => $user1->postcode,
            'address' => $user1->address,
        ]);
        $response = $this->get('/');
        $response->assertSee('Sold');
    }

    /** @test */
    public function purchase_items_add_to_profile()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'postcode' => 1234567,
            'address' => '北海道札幌市'
        ]);
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password')
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item = Item::create([
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $this->actingAs($user1)->get('/purchase/' . $item->id);
        $this->actingAs($user1)->post('/purchase', [
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postcode' => $user1->postcode,
            'address' => $user1->address,
        ]);
        $response = $this->actingAs($user1)->get('/mypage?page=buy');
        $response->assertSee('Item One');
    }

}
