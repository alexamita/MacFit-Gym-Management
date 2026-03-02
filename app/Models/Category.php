<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Category Model (Global)
 *
 * Represents a global fitness category shared across all gyms.
 * Used to classify entities like bundles and equipment) for
 * consistent organization and filtering across the entire system.
 *
 * Examples: Cardio, strength, Weight Loss, HIIT
 *
 *  Automatically generates a unique slug from the name.
 */

class Category extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        "name",
        "slug",
        "description",
    ];

    /**
     * Automatically handle slug generation.
     */
    protected static function booted(): void
    {
        // Create slug when creating a new category
        static::creating(function (Category $category) {
            // If slug is not provided, generate from name
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });

        // Optional: update slug when the name changes
        static::updating(function (Category $category) {
            // Only regenerate slug if name changed AND slug wasn't manually set
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    /**
     * Generate a unique slug for the categories table.
     *
     * @param string $name The category name.
     * @param int|null $ignoreId Record ID to ignore (useful on updates).
     */
    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        // Check if slug exists; if yes, append "-2", "-3", ...
        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**---------------
     * Relationships
    -----------------*/
    /**
     * Bundles Relationship
     * A category can have many bundles.
     * Assumes: bundles table has category_id foreign key.
     */
    public function bundles()
    {
        return $this->hasMany(Bundle::class);
    }

    /**
     * Equipment Relationship
     * A category can have multiple equipment.
     * Assumes: equipment table has category_id foreign key.
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
