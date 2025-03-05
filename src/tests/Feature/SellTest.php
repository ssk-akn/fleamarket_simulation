<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;

class SellTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function item_information_can_be_saved()
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create([
            'condition' => 'good'
        ]);

        $category = Category::create([
            'category' => 'kids'
        ]);

        $this->actingAs($user)->get('/sell');

        $image = UploadedFile::fake()->image('item.png');

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'Item One',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'categories' => [$category->id],
            'brand' => 'none',
            'price' => 999,
            'description' => 'Hello.',
            'image' => $image,
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Item One',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'brand' => 'none',
            'price' => 999,
            'description' => 'Hello.',
        ]);

        $item = Item::where('name', 'Item One')->first();
        $this->assertDatabaseHas('item_category', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);
    }
}
