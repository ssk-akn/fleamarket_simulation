<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class AddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function address_can_be_changed()
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

        $response = $this->followingRedirects()->actingAs($user1)->post('/purchase/address/' . $item->id, [
            'postcode' => 9876543,
            'address' => '沖縄県那覇市'
        ]);
        $response->assertSee('9876543');
        $response->assertSeeText('沖縄県那覇市');
    }

    /** @test */
    public function address_link_purchase_item()
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

        $this->followingRedirects()->actingAs($user1)->post('/purchase/address/' . $item->id, [
            'postcode' => 9876543,
            'address' => '沖縄県那覇市'
        ]);
        $this->actingAs($user1)->post('/purchase',[
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postcode' => session('new_postcode'),
            'address' => session('new_address'),
        ]);
        $response = $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'user_id' =>$user1->id,
            'postcode' => 9876543,
            'address' => '沖縄県那覇市'
        ]);
    }
}
