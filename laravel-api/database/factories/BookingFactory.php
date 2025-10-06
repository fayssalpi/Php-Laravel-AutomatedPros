<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Ticket;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),
            'ticket_id' => Ticket::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}