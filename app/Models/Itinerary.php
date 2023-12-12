<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Itinerary extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function itineraryPlaces(): HasMany
    {
        return $this->hasMany(ItineraryPlace::class);
    }
}
