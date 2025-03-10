<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;


class SearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function partial_match_search_by_item_name()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item1 = Item::create([
            'name' => 'Blue Shirt',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy1.jpg'
        ]);
        $item2 = Item::create([
            'name' => 'Red Shirt',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy2.jpg'
        ]);
        $item3 = Item::create([
            'name' => 'Green Pants',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy3.jpg'
        ]);

        $user->markEmailAsVerified();

        $response = $this->get('/?keyword=Shirt');

        $response->assertStatus(200);
        $response->assertSee('Blue Shirt');
        $response->assertSee('Red Shirt');
        $response->assertDontSee('Green Pants');
    }

    /** @test */
    public function search_state_is_preserved_on_mylist_page()
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $owner = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password')
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $item1 = Item::create([
            'name' => 'Blue Shirt',
            'user_id' => $owner->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy1.jpg'
        ]);
        $item2 = Item::create([
            'name' => 'Red Shirt',
            'user_id' => $owner->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy2.jpg'
        ]);
        $item3 = Item::create([
            'name' => 'Green Pants',
            'user_id' => $owner->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => '123',
            'description' => 'example',
            'image' => 'dummy3.jpg'
        ]);

        $user->markEmailAsVerified();

        $globalResponse = $this->get('/?keyword=Shirt');
        $globalResponse->assertStatus(200);
        $globalResponse->assertSee('Blue Shirt');
        $globalResponse->assertSee('Red Shirt');
        $globalResponse->assertDontSee('Green Pants');

        $user->likedItems()->attach($item1->id);

        $myListResponse = $this->actingAs($user)->get('/?page=mylist&keyword=Shirt');
        $myListResponse->assertStatus(200);
        $myListResponse->assertSee('Blue Shirt');
        $myListResponse->assertDontSee('Red Shirt');
        $myListResponse->assertDontSee('Green Pants');
    }
}
