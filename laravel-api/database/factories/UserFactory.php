<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('123456'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state(fn () => ['role' => 'admin']);
    }

    public function organizer()
    {
        return $this->state(fn () => ['role' => 'organizer']);
    }

    public function customer()
    {
        return $this->state(fn () => ['role' => 'customer']);
    }
}