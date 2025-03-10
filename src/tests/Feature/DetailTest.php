<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;


class DetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function item_details_are_display()
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

        $category = Category::create([
            'category' => 'book'
        ]);

        $item = Item::create([
            'name' => 'Item One',
            'user_id' => $user1->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $comment1 = Comment::create([
            'user_id' => $user2->id,
            'item_id' => $item->id,
            'comment' => 'Nice item.'
        ]);
        $comment2 = Comment::create([
            'user_id' => $user2->id,
            'item_id' => $item->id,
            'comment' => 'Fun item.'
        ]);

        $item->categories()->attach($category->id);

        $user2->likedItems()->attach($item->id);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee(asset('storage/dummy.png'));
        $response->assertSee('Item One');
        $response->assertSee('none');
        $response->assertSee(999);
        $response->assertSee(1);
        $response->assertSee(2);
        $response->assertSee('Item is funny.');
        $response->assertSee('book');
        $response->assertSee('good');
        $response->assertSee('User Two');
        $response->assertSee('Nice item.');
    }

    /** @test */
    public function can_display_multiple_category()
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

        $category1 = Category::create([
            'category' => 'book'
        ]);
        $category2 = Category::create([
            'category' => 'kitchen'
        ]);

        $item = Item::create([
            'name' => 'Item One',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Item is funny.',
            'image' => 'dummy.png'
        ]);

        $item->categories()->attach($category1);
        $item->categories()->attach($category2);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('book');
        $response->assertSee('kitchen');
    }
}
