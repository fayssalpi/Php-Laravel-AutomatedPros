<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class BookingService
{
    public function __construct(private BookingRepository $repo) {}

    // Customer books a ticket
    public function create(Request $request, int $ticketId)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        // Check available quantity
        if ($ticket->quantity < $data['quantity']) {
            abort(400, 'Not enough tickets available.');
        }

        // Deduct quantity
        $ticket->decrement('quantity', $data['quantity']);

        $booking = $this->repo->create([
            'user_id'  => $request->user()->id,
            'ticket_id'=> $ticketId,
            'quantity' => $data['quantity'],
            'status'   => 'pending',
        ]);

        return $booking->load('ticket.event');
    }

    // View customer’s bookings
    public function listForUser(Request $request)
    {
        return $this->repo->getByUser($request->user()->id);
    }

    // Cancel a booking
    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to cancel this booking.');
        }

        if ($booking->status === 'cancelled') {
            abort(400, 'This booking is already cancelled.');
        }

        // Restore ticket quantity
        $ticket = $booking->ticket;
        $ticket->increment('quantity', $booking->quantity);

        return $this->repo->update($booking, ['status' => 'cancelled']);
    }
}