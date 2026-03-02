<?php
// Bundle model representing a fitness bundle that users can subscribe to, with details about the bundle and its association to a category and gym
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Bundle extends Model
{
    protected $fillable = [
        'gym_id',
        'category_id',
        'name',
        'slug',
        'description',
        'gym_zone',
        'start_time',
        'session_duration',
        'price',
        'currency',
    ];

    /**
     * Type casting
     */
    protected $casts = [
        'gym_id' => 'integer',
        'category_id' => 'integer',
        'start_time' => 'datetime:H:i',
        'session_duration' => 'integer',
        'price' => 'decimal:2',
    ];

    /**---------------
     * Relationships
    -----------------*/
    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**------
     * Scopes
    --------*/
    /**
     * Global bundles (available across all gyms)
     */
    public function scopeGlobal(Builder $query)
    {
        return $query->whereNull('gym_id');
    }

    /**
     * Bundles for a specific gym (including global ones)
     */
    public function scopeForGym(Builder $query, int $gymId)
    {
        return $query->where(function ($q) use ($gymId) {
            $q->where('gym_id', $gymId)
            ->orWhereNull('gym_id');
        });
    }

    /**----------
     * Accessors
    -------------*/
    /**
     * Get formatted price (e.g., KES 1,500.00)
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->currency . ' ' . number_format((float) $this->price, 2);
    }
}
