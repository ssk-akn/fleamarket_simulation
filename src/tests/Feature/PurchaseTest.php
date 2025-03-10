<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Order;
use Stripe\Checkout\Session as StripeSession;
use Mockery;

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

        $this->actingAs($user1)->get('/purchase/' . $item->id);

        $fakeSession = (object)[
            'url' => 'http://fake-checkout-session-url.com',
            'metadata' => (object)[
                'user_id' => $user1->id,
                'item_id' => $item->id,
                'postcode' => $user1->postcode,
                'address' => $user1->address,
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
            'postcode' => $user1->postcode,
            'address' => $user1->address,
        ]);
        $checkoutResponse->assertRedirect();

        $successResponse = $this->actingAs($user1)
            ->get('/purchase/success?session_id=fake_session_id');
        $successResponse->assertRedirect('/mypage');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user1->id,
            'item_id' => $item->id,
            'payment' => 'card',
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

        $this->actingAs($user1)->get('/purchase/' . $item->id);

        $fakeSession = (object)[
            'url' => 'http://fake-checkout-session-url.com',
            'metadata' => (object)[
                'user_id' => $user1->id,
                'item_id' => $item->id,
                'postcode' => $user1->postcode,
                'address' => $user1->address,
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
            'postcode' => $user1->postcode,
            'address' => $user1->address,
        ]);
        $checkoutResponse->assertRedirect();

        $successResponse = $this->actingAs($user1)
            ->get('/purchase/success?session_id=fake_session_id');

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

        $this->actingAs($user1)->get('/purchase/' . $item->id);

        $fakeSession = (object)[
            'url' => 'http://fake-checkout-session-url.com',
            'metadata' => (object)[
                'user_id' => $user1->id,
                'item_id' => $item->id,
                'postcode' => $user1->postcode,
                'address' => $user1->address,
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
            'postcode' => $user1->postcode,
            'address' => $user1->address,
        ]);
        $checkoutResponse->assertRedirect();

        $successResponse = $this->actingAs($user1)
            ->get('/purchase/success?session_id=fake_session_id');

        $response = $this->actingAs($user1)->get('/mypage?page=buy');
        $response->assertSee('Item One');
    }

}
