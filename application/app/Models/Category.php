<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'race_edition_id',
    'code',
    'name',
    'description',
    'distance_km',
    'technique',
    'entry_fee_cents',
    'sort_order',
])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    public function raceEdition(): BelongsTo
    {
        return $this->belongsTo(RaceEdition::class);
    }

    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:2',
            'entry_fee_cents' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
