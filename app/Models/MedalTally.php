<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedalTally extends Model
{
    protected $guarded = [];

    public function getTotalAttribute(): int
    {
        return $this->gold + $this->silver + $this->bronze;
    }
}
