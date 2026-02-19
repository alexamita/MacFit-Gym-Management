<?php
// Equipment model representing gym equipment that can be used in bundles and tracked for maintenance and inventory purposes, associated with a specific gym

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        "name",
        "usage",
        "model_no",
        "value",
        "status",
        "gym_id",
    ];
}
