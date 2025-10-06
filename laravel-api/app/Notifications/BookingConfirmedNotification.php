<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable)
    {
        return ['mail']; // you can add 'database' later
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Booking is Confirmed 🎟️')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking for ' . $this->booking->ticket->event->title . ' has been confirmed.')
            ->line('Quantity: ' . $this->booking->quantity)
            ->line('Thank you for booking with us!')
            ->salutation('– ArchiSoftwares Team');
    }
}