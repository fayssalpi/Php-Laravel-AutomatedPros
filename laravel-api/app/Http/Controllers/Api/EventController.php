<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(private EventService $service) {}

    // GET /api/events
    public function index(Request $request)
    {
        return response()->json($this->service->list($request));
    }

    // GET /api/events/{id}
    public function show($id)
    {
        $event = $this->service->show($id);
        return response()->json($event);
    }

    // POST /api/events
    public function store(Request $request)
    {
        $event = $this->service->create($request);
        return response()->json($event, 201);
    }

    // PUT /api/events/{id}
    public function update(Request $request, Event $event)
    {
        $event = $this->service->update($request, $event);
        return response()->json($event);
    }

    // DELETE /api/events/{id}
    public function destroy(Request $request, Event $event)
    {
        $this->service->delete($request, $event);
        return response()->json(['message' => 'Event deleted successfully']);
    }
}