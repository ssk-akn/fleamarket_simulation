<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function user_can_add_favorite()
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
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $user1->markEmailAsVerified();

        $this->actingAs($user1)->get('/item/' . $item->id);

        $response = $this->actingAs($user1)->post('/item/' . $item->id . '/like');

        $response->assertRedirect('/item/' . $item->id);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user1->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function favorite_icon_change_color()
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
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $user1->markEmailAsVerified();

        $response = $this->actingAs($user1)->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertSeeInOrder(['star.png']);

        $this->actingAs($user1)->post('/item/' . $item->id . '/like');

        $response = $this->actingAs($user1)->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertSeeInOrder(['y_star.png']);
    }

    /** @test */
    public function user_can_remove_favorite()
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
            'name' => 'Item One',
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $user1->markEmailAsVerified();

        $this->actingAs($user1)->get('/item/' . $item->id);
        $this->actingAs($user1)->post('/item/' . $item->id . '/like');

        $this->assertDatabaseHas('likes', [
            'user_id' => $user1->id,
            'item_id' => $item->id,
        ]);
        $response = $this->get('/item/' . $item->id);
        $response->assertSee(1);

        $this->actingAs($user1)->post('/item/' . $item->id . '/unlike');
        $response = $this->get('/item/' . $item->id);
        $response->assertSee(0);
    }
}
