<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getChairNameAttribute(): string
    {
        return 'Kursi '.($this->id ?? 1);
    }

    public function getDisplayNameWithChairAttribute(): string
    {
        return "Kursi {$this->id} · {$this->name}";
    }

    public function getChairCodeLabelAttribute(): string
    {
        return sprintf('%02d', $this->id ?? 1);
    }

    public function getFilterPillLabelAttribute(): string
    {
        return "{$this->name} (".sprintf('%02d', $this->id ?? 1).')';
    }
}
