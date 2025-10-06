<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventRepository
{
    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Event::query()->with('tickets');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%'.$filters['search'].'%')
                  ->orWhere('description', 'like', '%'.$filters['search'].'%');
            });
        }

        if (!empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', '%'.$filters['location'].'%');
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Event
    {
        return Event::with('tickets')->find($id);
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(Event $event, array $data): Event
    {
        $event->update($data);
        return $event;
    }

    public function delete(Event $event): void
    {
        $event->delete();
    }
}