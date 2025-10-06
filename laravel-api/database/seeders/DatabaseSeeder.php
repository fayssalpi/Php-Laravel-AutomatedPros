<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Payment; // optional if you’ll use it later


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        $admins = User::factory()->count(2)->admin()->create();
        $organizers = User::factory()->count(3)->organizer()->create();
        $customers = User::factory()->count(10)->customer()->create();

        // Events (linked to random organizers)
        $events = Event::factory()->count(5)->create([
            'created_by' => $organizers->random()->id,
        ]);

        // Tickets (3 per event)
        $events->each(function ($event) {
            Ticket::factory()->count(3)->create(['event_id' => $event->id]);
        });

        // Bookings (linked to random customers & tickets)
        $tickets = \App\Models\Ticket::all();
        Booking::factory()->count(20)->make()->each(function ($booking) use ($customers, $tickets) {
            $booking->user_id = $customers->random()->id;
            $booking->ticket_id = $tickets->random()->id;
            $booking->save();
        });

        $this->command->info('✅ Database seeded: 2 admins, 3 organizers, 10 customers, 5 events, 15 tickets, 20 bookings');
    }

}
