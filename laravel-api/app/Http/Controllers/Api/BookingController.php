<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $service) {}

    // POST /api/tickets/{id}/bookings
    public function store(Request $request, $ticketId)
    {
        $booking = $this->service->create($request, $ticketId);
        return response()->json($booking, 201);
    }

    // GET /api/bookings
    public function index(Request $request)
    {
        return response()->json($this->service->listForUser($request));
    }

    // PUT /api/bookings/{id}/cancel
    public function cancel(Request $request, Booking $booking)
    {
        $booking = $this->service->cancel($request, $booking);
        return response()->json(['message' => 'Booking cancelled', 'booking' => $booking]);
    }
}