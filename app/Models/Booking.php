<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_BREAK = 'break';

    protected $fillable = [
        'booking_code',
        'customer_id',
        'barber_id',
        'service_id',
        'schedule_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isBreak(): bool
    {
        return $this->status === self::STATUS_BREAK;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_CONFIRMED => 'Mendatang',
            self::STATUS_IN_PROGRESS => 'Sedang Cukur',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_REJECTED => 'Dibatalkan',
            self::STATUS_BREAK => 'Jeda Terjadwal',
            default => ucfirst((string) $this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'badge-pending',
            self::STATUS_CONFIRMED => 'badge-confirmed',
            self::STATUS_IN_PROGRESS => 'badge-in-progress',
            self::STATUS_COMPLETED => 'badge-completed',
            self::STATUS_REJECTED => 'badge-rejected',
            self::STATUS_BREAK => 'badge-break',
            default => 'badge-secondary',
        };
    }
}
