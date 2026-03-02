<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gym extends Model
{
    protected $fillable = [
        "name",
        "location",
        "phone_number",
        "description",
    ];

/**------------------
     * Relationships
---------------------*/
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // A gym has many bundles
    public function bundles(): HasMany
    {
        return $this->hasMany(Bundle::class);
    }
}
