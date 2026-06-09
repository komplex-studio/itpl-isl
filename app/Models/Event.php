<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_open' => 'boolean',
    ];

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function fixtures(): HasMany
    {
        return $this->hasMany(Fixture::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDateRangeAttribute(): string
    {
        if ($this->start_date->isSameMonth($this->end_date)) {
            return $this->start_date->format('d').'–'.$this->end_date->format('d M Y');
        }

        return $this->start_date->format('d M').' – '.$this->end_date->format('d M Y');
    }
}
