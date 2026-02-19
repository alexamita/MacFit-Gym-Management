<?php
// Gym model representing a gym location that can have bundles, equipment, and subscriptions associated with it for the gym management system, with details about the gym's name, location, and description

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    protected $fillable = [
        "name",
        "longitude",
        "latitude",
        "description",
        "gym_id"
    ];
}
