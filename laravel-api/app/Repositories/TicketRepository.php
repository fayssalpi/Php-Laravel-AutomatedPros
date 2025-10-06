<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository
{
    public function getByEvent(int $eventId)
    {
        return Ticket::where('event_id', $eventId)->get();
    }

    public function findById(int $id): ?Ticket
    {
        return Ticket::with('event')->find($id);
    }

    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket;
    }

    public function delete(Ticket $ticket): void
    {
        $ticket->delete();
    }
}