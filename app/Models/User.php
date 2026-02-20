<?php
// User model representing a user of the fitness subscription service or gym management staff, with authentication and API token capabilities for secure access to the application and its features
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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

    // Define the relationship between the User model and the Role model, indicating that each user belongs to a specific role which determines their permissions and access levels within the application
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Define a method to return the user's abilities based on their role, which can be used for authorization checks throughout the application to control access to various features and resources
    public function abilities()
    {
        return[
            'ADMIN' => $this->role->id === 1,
            'GYM_MANAGER' => $this->role->id === 2,
            'TRAINER' => $this->role->id === 3,
            'STAFF' => $this->role->id === 4,
            'MEMBER' => $this->role->id === 5,
            'USER' => $this->role->id === 6,
        ];
    }
}
