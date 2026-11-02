<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barber extends Model
{
    use HasFactory;

    public const STATUS_ONLINE = 'online';

    public const STATUS_CUTI = 'cuti';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'name',
        'chair_number',
        'role_title',
        'photo',
        'description',
        'specialization',
        'skills_tags',
        'availability_status',
        'rating',
        'review_count',
        'scheduled_sessions_count',
        'accept_public_booking',
        'warning_note',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'chair_number' => 'integer',
            'rating' => 'decimal:2',
            'review_count' => 'integer',
            'scheduled_sessions_count' => 'integer',
            'accept_public_booking' => 'boolean',
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

    public function getChairCodeLabelAttribute(): string
    {
        $num = $this->chair_number ?: $this->id ?: 1;

        return sprintf('%02d', $num);
    }

    public function getChairNameAttribute(): string
    {
        return 'Kursi '.$this->chair_code_label;
    }

    public function getDisplayNameWithChairAttribute(): string
    {
        return "Kursi {$this->chair_code_label} · {$this->name}";
    }

    public function getFilterPillLabelAttribute(): string
    {
        return "{$this->name} ({$this->chair_code_label})";
    }

    /**
     * @return array<int, string>
     */
    public function getSkillsArrayAttribute(): array
    {
        if (empty($this->skills_tags)) {
            return [];
        }

        if (str_starts_with($this->skills_tags, '[') && str_ends_with($this->skills_tags, ']')) {
            $decoded = json_decode($this->skills_tags, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return array_map('trim', explode(',', $this->skills_tags));
    }

    public function getStatusBadgeLabelAttribute(): string
    {
        return match ($this->availability_status) {
            self::STATUS_CUTI => 'CUTI',
            self::STATUS_NONAKTIF => 'CUTI / NONAKTIF',
            default => 'ONLINE',
        };
    }

    public function getEffectiveSessionsCountAttribute(): int
    {
        if ($this->scheduled_sessions_count > 0) {
            return $this->scheduled_sessions_count;
        }

        return $this->bookings()
            ->whereDate('booking_date', today())
            ->whereNotIn('status', [Booking::STATUS_REJECTED])
            ->count();
    }
}
