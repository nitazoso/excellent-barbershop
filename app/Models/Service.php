<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    public const CAT_HAIRCUT = 'haircut_styling';

    public const CAT_BEARD = 'beard_shaving';

    public const CAT_TREATMENT = 'treatment_others';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
        'category',
        'badge',
        'is_featured',
        'show_in_widget',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_widget' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            self::CAT_BEARD => 'BEARD & SHAVING',
            self::CAT_TREATMENT => 'TREATMENT & DETOX',
            default => 'HAIRCUT & STYLING',
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute(): string
    {
        return $this->duration_minutes.' MNT';
    }
}
