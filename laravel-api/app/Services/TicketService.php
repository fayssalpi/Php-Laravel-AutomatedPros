<?php

namespace App\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;

class TicketService
{
    public function __construct(private TicketRepository $repo) {}

    public function create(Request $request, int $eventId)
    {
        $data = $request->validate([
            'type' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $data['event_id'] = $eventId;

        $event = $request->user()->events()->find($eventId);
        if ($request->user()->role !== 'admin' && !$event) {
            abort(403, 'You are not authorized to add tickets to this event.');
        }

        return $this->repo->create($data);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'type' => 'sometimes|string|max:100',
            'price' => 'sometimes|numeric|min:0',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        // Check ownership
        $user = $request->user();
        if ($user->role !== 'admin' && $ticket->event->created_by !== $user->id) {
            abort(403, 'You are not authorized to update this ticket.');
        }

        return $this->repo->update($ticket, $data);
    }

    public function delete(Request $request, Ticket $ticket)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $ticket->event->created_by !== $user->id) {
            abort(403, 'You are not authorized to delete this ticket.');
        }

        $this->repo->delete($ticket);
    }
}