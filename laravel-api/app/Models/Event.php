<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};


class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = ['title','description','date','location','created_by'];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }


}
