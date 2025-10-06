<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};


class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = ['type','price','quantity','event_id'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


}
