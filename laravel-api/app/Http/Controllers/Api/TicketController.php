<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private TicketService $service) {}

    // POST /api/events/{event_id}/tickets
    public function store(Request $request, $eventId)
    {
        $ticket = $this->service->create($request, $eventId);
        return response()->json($ticket, 201);
    }

    // PUT /api/tickets/{id}
    public function update(Request $request, Ticket $ticket)
    {
        $ticket = $this->service->update($request, $ticket);
        return response()->json($ticket);
    }

    // DELETE /api/tickets/{id}
    public function destroy(Request $request, Ticket $ticket)
    {
        $this->service->delete($request, $ticket);
        return response()->json(['message' => 'Ticket deleted successfully']);
    }
}