<?php
// Bundle model representing a fitness bundle that users can subscribe to, with details about the bundle and its association to a category and gym
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Bundle extends Model
{
    protected $fillable = [
        "name",
        "location",
        "start_time",
        "session_duration",
        "description",
        "category_id",
        "gym_id",
    ];

}
