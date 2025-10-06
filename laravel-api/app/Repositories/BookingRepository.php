<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    public function getByUser(int $userId): Collection
    {
        return Booking::with(['ticket.event', 'payment'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function find(int $id): ?Booking
    {
        return Booking::with(['ticket.event', 'payment'])->find($id);
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);
        return $booking;
    }
}