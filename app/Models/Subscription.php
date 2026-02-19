<?php
// Subscription model representing a user's subscription to a fitness bundle, tracking status and subscription date for the gym management system, with details about the user, bundle, and subscription status

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        "user_id",
        "bundle_id",
        "status",
        "subscribed_at",
    ];
}
