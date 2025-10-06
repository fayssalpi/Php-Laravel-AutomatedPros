<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\BookingController;


// =========================
// AUTH ROUTES
// =========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// =========================
// PROTECTED ROUTES
// =========================
Route::middleware('auth:sanctum')->group(function () {

    // User info & logout
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // =========================
    // EVENT ROUTES
    // =========================
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);


    // Organizer/Admin only
    Route::middleware('role:organizer,admin')->group(function () {
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy']);

        // Ticket management
        Route::post('/events/{event_id}/tickets', [TicketController::class, 'store']);
        Route::put('/tickets/{ticket}', [TicketController::class, 'update']);
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);
    });

    // =========================
    // BOOKING ROUTES (Customer)
    // =========================
    Route::middleware(['role:customer', 'prevent.double.booking'])->group(function () {
        Route::post('/tickets/{ticket}/bookings', [BookingController::class, 'store']);
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

        // 💰 Payments
        Route::post('/bookings/{booking}/payment', [PaymentController::class, 'store']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);
    });

});