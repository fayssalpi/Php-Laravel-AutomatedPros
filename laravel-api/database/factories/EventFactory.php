<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class EventFactory extends Factory
{
    public function definition(): array
    {
        // pick random existing organizer if any, otherwise create one
        $organizerId = User::where('role', 'organizer')->inRandomOrder()->value('id')
            ?? User::factory()->organizer()->create()->id;

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'location' => fake()->city(),
            'created_by' => $organizerId,
        ];
    }
}