<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Booking;

class PreventDoubleBooking
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $userId   = $request->user()->id ?? null;
        $ticketId = $request->route('ticket') ?? $request->input('ticket_id');

        if ($userId && $ticketId) {
            $existing = Booking::where('user_id', $userId)
                ->where('ticket_id', $ticketId)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'You have already booked this ticket.'
                ], 409);
            }
        }

        return $next($request);
    }
}