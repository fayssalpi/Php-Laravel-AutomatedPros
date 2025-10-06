<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use App\Notifications\BookingConfirmedNotification;

class PaymentService
{
    public function __construct(private PaymentRepository $repo) {}

    // 🔹 Mock payment creation
    public function create(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to pay for this booking.');
        }

        if ($booking->status === 'cancelled') {
            abort(400, 'Cannot pay for a cancelled booking.');
        }

        // simulate a payment gateway response
        $amount = $booking->ticket->price * $booking->quantity;

        $payment = $this->repo->create([
            'booking_id' => $booking->id,
            'amount'     => $amount,
            'status'     => 'success',   // mock successful payment
        ]);

        // mark booking confirmed
        $booking->update(['status' => 'confirmed']);

        $booking->user->notify(new \App\Notifications\BookingConfirmedNotification($booking));

        return $payment->load('booking.ticket.event');
    }

    // 🔹 Retrieve payment
    public function show(int $id)
    {
        $payment = $this->repo->find($id);
        if (! $payment) {
            abort(404, 'Payment not found.');
        }
        return $payment;
    }
}