<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Subscription Model
 *
 * Represents a user's subscription to a bundle.
 * Gym is derived via: $subscription->bundle->gym
 */
class Subscription extends Model
{
    /**
     * Status constants (avoid magic strings)
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        "user_id",
        "bundle_id",
        "status",
        "starts_at",
        "expires_at",
    ];

    /**
     * Casts
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**---------------
     * Relationships
    -----------------*/
    /**
     * Subscription belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Subscription belongs to a bundle.
     */
    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    /**
     * Access gym via bundle.
     */
    public function gym()
    {
        return $this->bundle?->gym;
    }

    /**----------------------
     * Status Helpers
    -------------------------*/
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    /**----------------------
     * Query Scopes
    -------------------------*/
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)
        ->where('expires_at', '>', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    protected static function booted(): void
{
    static::retrieved(function (Subscription $subscription) {
        if (
            $subscription->status === self::STATUS_ACTIVE &&
            $subscription->expires_at->isPast()
        ) {
            $subscription->update(['status' => self::STATUS_EXPIRED]);
        }
    });
}
}
