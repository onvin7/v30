<?php

namespace App\Models;

use App\Enums\RaceEditionStatus;
use Database\Factories\RaceEditionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'event_id',
    'year',
    'event_date',
    'date_confirmed',
    'is_current',
    'status',
    'venue',
    'course_summary',
    'fee_notes',
])]
class RaceEdition extends Model
{
    /** @use HasFactory<RaceEditionFactory> */
    use HasFactory;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'date_confirmed' => 'boolean',
            'is_current' => 'boolean',
            'status' => RaceEditionStatus::class,
        ];
    }
}
