<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fixture extends Model
{
    protected $guarded = [];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function athleteA(): BelongsTo
    {
        return $this->belongsTo(Athlete::class, 'athlete_a_id');
    }

    public function athleteB(): BelongsTo
    {
        return $this->belongsTo(Athlete::class, 'athlete_b_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Athlete::class, 'winner_id');
    }
}
