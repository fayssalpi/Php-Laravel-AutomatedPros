<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Booking;
use App\Repositories\PaymentRepository;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingConfirmedNotification;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    /** ✅ Successful payment flow */
    public function test_it_confirms_booking_and_creates_payment()
    {
        Notification::fake();

        $user = User::factory()->customer()->create();
        $event = Event::factory()->create(['created_by' => User::factory()->organizer()->create()->id]);
        $ticket = Ticket::factory()->create(['event_id' => $event->id, 'price' => 50]);
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'quantity' => 2,
            'status' => 'pending',
        ]);

        $service = new PaymentService(new PaymentRepository);

        $request = Request::create('/pay', 'POST');
        $request->setUserResolver(fn () => $user);

        $payment = $service->create($request, $booking);

        $this->assertEquals('success', $payment->status);
        $this->assertEquals(100.00, (float) $payment->amount);
        $this->assertEquals('confirmed', $booking->fresh()->status);

        Notification::assertSentTo($user, BookingConfirmedNotification::class);
    }

    /** 🚫 Prevent unauthorized users from paying */
    public function test_it_blocks_payment_for_unauthorized_user()
    {
        $customer = User::factory()->customer()->create();
        $otherUser = User::factory()->customer()->create();
        $event = Event::factory()->create(['created_by' => User::factory()->organizer()->create()->id]);
        $ticket = Ticket::factory()->create(['event_id' => $event->id, 'price' => 50]);
        $booking = Booking::factory()->create(['user_id' => $customer->id, 'ticket_id' => $ticket->id]);

        $service = new PaymentService(new PaymentRepository);
        $request = Request::create('/pay', 'POST');
        $request->setUserResolver(fn () => $otherUser);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $service->create($request, $booking);
    }

    /** 🚫 Prevent payment on cancelled booking */
    public function test_it_blocks_payment_for_cancelled_booking()
    {
        $user = User::factory()->customer()->create();
        $event = Event::factory()->create(['created_by' => User::factory()->organizer()->create()->id]);
        $ticket = Ticket::factory()->create(['event_id' => $event->id]);
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'status' => 'cancelled',
        ]);

        $service = new PaymentService(new PaymentRepository);
        $request = Request::create('/pay', 'POST');
        $request->setUserResolver(fn () => $user);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $service->create($request, $booking);
    }
}