<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Order;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function user_can_get_my_profile()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'postcode' => 1234567,
            'address' => '東京都新宿区',
            'image' => 'image.png'
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

        $sellItem = Item::create([
            'name' => 'Item Sell',
            'user_id' => $user1->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy1.png'
        ]);
        $buyItem = Item::create([
            'name' => 'Item Buy',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy2.png'
        ]);

        Order::create([
            'user_id' => $user1->id,
            'item_id' => $buyItem->id,
            'payment' => 'カード支払い',
            'postcode' => $user1->postcode,
            'address' => $user1->address
        ]);

        $user1->markEmailAsVerified();

        $response = $this->actingAs($user1)->get('/mypage');
        $response->assertSee(asset('storage/image.png'));
        $response->assertSee('User One');

        $response = $this->actingAs($user1)->get('/mypage?page=buy');
        $response->assertSee('Item Buy');
        $response->assertDontSee('Item Sell');

        $response = $this->actingAs($user1)->get('/mypage?page=sell');
        $response->assertSee('Item Sell');
        $response->assertDontSee('Item Buy');
    }

    /** @test */
    public function users_initial_value_display()
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'postcode' => 1234567,
            'address' => '東京都新宿区',
            'image' => 'image.png'
        ]);

        $user->markEmailAsVerified();

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertSee(asset('storage/image.png'));
        $response->assertSee('User One');
        $response->assertSee('1234567');
        $response->assertSee('東京都新宿区');
    }
}
