<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class EventService
{
    public function __construct(private EventRepository $repo) {}

    // 🔹 List events with caching
    public function list(Request $request)
    {
        $filters = $request->only(['search', 'date', 'location']);
        $perPage = $request->get('per_page', 10);

        // Build a unique cache key based on filters + pagination
        $cacheKey = 'events_list_' . md5(json_encode($filters) . $perPage . '_' . $request->get('page', 1));

        return Cache::remember($cacheKey, now()->addMinutes(1), function () use ($filters, $perPage) {
            return $this->repo->getAll($filters, $perPage);
        });
    }

    // 🔹 Show single event (no need to cache individually)
    public function show(int $id)
    {
        $event = $this->repo->findById($id);
        if (!$event) {
            throw ValidationException::withMessages(['id' => 'Event not found']);
        }
        return $event;
    }

    // 🔹 Create new event + clear cache
    public function create(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
        ]);

        $data['created_by'] = $request->user()->id;

        $event = $this->repo->create($data);

        // ❗ Invalidate all event caches
        Cache::flush();

        return $event;
    }

    // 🔹 Update event + clear cache
    public function update(Request $request, Event $event)
    {
        $user = $request->user();

        if ($user->role !== 'admin' && $event->created_by !== $user->id) {
            abort(403, 'You are not authorized to update this event.');
        }

        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'sometimes|date',
            'location'    => 'sometimes|string|max:255',
        ]);

        $updated = $this->repo->update($event, $data);

        Cache::flush(); // clear cache when event changes

        return $updated;
    }

    // 🔹 Delete event + clear cache
    public function delete(Request $request, Event $event)
    {
        $user = $request->user();

        if ($user->role !== 'admin' && $event->created_by !== $user->id) {
            abort(403, 'You are not authorized to delete this event.');
        }

        $this->repo->delete($event);

        Cache::flush(); // clear cache on deletion
    }
}