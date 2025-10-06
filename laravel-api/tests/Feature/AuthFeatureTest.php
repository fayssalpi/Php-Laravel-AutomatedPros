<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123456',
            'phone' => '0612345678',
            'role' => 'customer',
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token'
        ]);
    }

    public function test_user_can_login()
    {
        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123456',
            'phone' => '0612345678',
            'role' => 'customer',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }
}