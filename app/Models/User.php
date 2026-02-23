<?php
// This code defines a User model in a Laravel application, which represents the users of the system. It includes properties for mass assignment, hidden attributes, and casts for data types. The model also defines relationships and methods to determine user abilities based on their role.
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'user_image',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    // Cast the email_verified_at attribute to a datetime object, the password to a hashed value, and is_active to a boolean for proper data handling and security in the application
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];
    // Define an accessor to determine if the user has an admin role, which can be used throughout the application to check for administrative privileges and control access to certain features or resources
    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->role?->name === 'ADMIN', // Check if the user's role name is 'ADMIN' to determine if they have admin privileges
        );
    }

    // Define the relationship between the User model and the Role model, indicating that each user belongs to a specific role which determines their permissions and access levels within the application
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Define a method to return the user's abilities based on their role, which can be used for authorization checks throughout the application to control access to various features and resources
    public function abilities()
    {
        return[
            // Null-safe operator (?->) to prevent crashes if a user has no role
            'ADMIN' => $this->role?->name === 'ADMIN',
            'GYM_MANAGER' => $this->role?->name === 'GYM_MANAGER',
            'TRAINER' => $this->role?->name === 'TRAINER',
            'STAFF' => $this->role?->name === 'STAFF',
            'MEMBER' => $this->role?->name === 'MEMBER',
            'USER' => $this->role?->name === 'USER',
        ];
    }
}
