<?php
// Role model representing a user role that can be assigned to users for access control and permissions within the gym management system, with details about the role's name and description

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
            "name",
            "description"
        ];
}
