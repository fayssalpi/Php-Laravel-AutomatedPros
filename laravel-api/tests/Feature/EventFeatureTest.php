<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_event()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        Sanctum::actingAs($organizer);

        $response = $this->postJson('/api/events', [
            'title' => 'Music Fest 2025',
            'description' => 'Annual music event',
            'date' => now()->addDays(10)->toDateString(),
            'location' => 'Agadir',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Music Fest 2025']);
    }

    public function test_any_user_can_view_events()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    
        Event::factory()->count(3)->create();
    
        $response = $this->getJson('/api/events');
    
        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }
}