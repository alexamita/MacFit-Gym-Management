<?php
// Equipment model representing gym equipment that can be used in bundles and tracked for maintenance and inventory purposes, associated with a specific gym

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * Equipment Model
 *
 * Represents a physical piece of gym equipment.
 * Each equipment item:
 * - Belongs to one gym
 * - Belongs to one global category
 * - Has a globally unique manufacturer serial number
 * - May have a gym-specific internal asset code
 */
class Equipment extends Model
{
    /**
     * Equipment status constants.
     * Prevents magic strings across the application.
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_UNDER_MAINTENANCE = 'under_maintenance';
    public const STATUS_FAULTY = 'faulty';
    public const STATUS_DECOMMISSIONED = 'decommisioned';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        "name",
        "usage_notes",
        "manufacturer_serial_no",
        "asset_code",
        "value",
        "status",
        "gym_id",
        "category_id",
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'value' => 'decimal:2',
    ];

    /**---------------
     * Relationships
    -----------------*/
    /**
     * Each equipment item belongs to one gym.
     */
    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    /**
     * Each equipment item belongs to one category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**--------------
     *  Query Scopes
    -----------------*/
    /**
     * Scope for active equipment.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for faulty equipment.
     */
    public function scopeFaulty($query)
    {
        return $query->where('status', self::STATUS_FAULTY);
    }
}
