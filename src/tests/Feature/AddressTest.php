<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Mockery;

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
            'email_verified_at' => now(),
            'postcode' => 1234567,
            'address' => '北海道札幌市',
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
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $user1->markEmailAsVerified();

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
            'email_verified_at' => now(),
            'postcode' => 1234567,
            'address' => '北海道札幌市'
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
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $user1->markEmailAsVerified();

        $this->followingRedirects()->actingAs($user1)->post('/purchase/address/' . $item->id, [
            'postcode' => 9876543,
            'address' => '沖縄県那覇市'
        ]);

        $fakeSession = (object)[
            'url' => 'http://fake-checkout-session-url.com',
            'metadata' => (object)[
                'user_id' => $user1->id,
                'item_id' => $item->id,
                'postcode' => session('new_postcode'),
                'address' => session('new_address'),
                'building' => '',
            ],
            'payment_method_types' => ['card'],
        ];

        $mock = Mockery::mock('alias:\Stripe\Checkout\Session');
        $mock->shouldReceive('create')
            ->andReturn($fakeSession);
        $mock->shouldReceive('retrieve')
            ->with('fake_session_id')
            ->andReturn($fakeSession);

        $checkoutResponse = $this->actingAs($user1)->post('/purchase/checkout', [
            'item_id' => $item->id,
            'payment' => 'カード支払い',
            'postcode' => session('new_postcode'),
            'address' => session('new_address'),
            'building' => '',
        ]);
        $checkoutResponse->assertRedirect();

        $successResponse = $this->actingAs($user1)
            ->get('/purchase/success?session_id=fake_session_id');
        $successResponse->assertRedirect('/mypage');

        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'user_id' =>$user1->id,
            'postcode' => 9876543,
            'address' => '沖縄県那覇市'
        ]);
    }
}
