<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $service) {}

    // POST /api/bookings/{id}/payment
    public function store(Request $request, Booking $booking)
    {
        $payment = $this->service->create($request, $booking);
        return response()->json([
            'message' => 'Payment processed successfully',
            'payment' => $payment
        ], 201);
    }

    // GET /api/payments/{id}
    public function show($id)
    {
        $payment = $this->service->show($id);
        return response()->json($payment);
    }
}