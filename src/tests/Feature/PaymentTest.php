<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class PaymentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function displays_selected_payment_method()
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

        $this->actingAs($user1)->get('/purchase/' . $item->id);
        $response = $this->followingRedirects()->actingAs($user1)->post('/purchase/update-payment', [
            'payment' => 'カード支払い',
            'item_id' => $item->id,
        ]);
        $response->assertSee('<td class="confirm__date">カード支払い</td>', false);
    }
}
