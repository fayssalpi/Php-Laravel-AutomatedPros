<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Booking;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingAndPaymentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_book_ticket_and_pay()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $customer = User::factory()->create(['role' => 'customer']);

        $event = Event::factory()->create(['created_by' => $organizer->id]);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 100,
            'quantity' => 10,
            'type' => 'VIP'
        ]);

        Sanctum::actingAs($customer);

        // Book ticket
        $bookingResponse = $this->postJson("/api/tickets/{$ticket->id}/bookings", [
            'quantity' => 2
        ]);

        $bookingResponse->assertStatus(201);
        $bookingId = $bookingResponse['id'] ?? $bookingResponse['booking']['id'] ?? null;

        $this->assertNotNull($bookingId, 'Booking ID should be returned');

        // Pay for booking
        $paymentResponse = $this->postJson("/api/bookings/{$bookingId}/payment");

        $paymentResponse->assertStatus(201)
                        ->assertJsonFragment(['status' => 'success']);
    }
}