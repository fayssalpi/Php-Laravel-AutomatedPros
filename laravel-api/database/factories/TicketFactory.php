<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['VIP', 'Standard', 'Economy']),
            'price' => fake()->randomFloat(2, 10, 300),
            'quantity' => fake()->numberBetween(10, 200),
            'event_id' => Event::factory(),
        ];
    }
}