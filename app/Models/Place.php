<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function destinationPreference(): BelongsTo
    {
        return $this->belongsTo(DestinationPreference::class, 'destination_preference_id', 'id');
    }

    public function placeImages(): HasMany
    {
        return $this->hasMany(PlaceImage::class);
    }
}
