<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
    }
}
