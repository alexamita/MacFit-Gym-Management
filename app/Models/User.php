<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * User Model
 *
 * Represents an authenticated system user within the gym management system.
 *
 * Architecture:
 * - Each user belongs to exactly ONE role (role_id foreign key).
 * - Role determines authorization and abilities.
 * - Email verification is enforced.
 * - Sanctum is used for API authentication.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Mass assignable attributes.
     * These fields can be safely assigned via create() or update().
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'user_image',
        'role_id',
        'gym_id',
    ];

    /**
     * Attributes hidden from JSON serialization.
     * Prevents exposure of sensitive data in API responses.
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting rules.
     * Ensures proper data typing and automatic transformations.
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];



    /**---------------
     * Relationships
    -----------------*/
    /**
     * Role Relationship
     * Each user belongs to a single role.
     * withTrashed() allows access to role even if it was soft-deleted, which preserves historical integrity.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }



    /**----------------------
     * Authorization Helpers
    -------------------------*/
    /**
     * Computed attribute: is_admin
     * Allows access like: $user->isadmin
     */
    protected function isAdmin(): Attribute
    {
        return Attribute::make(get: fn () => $this->role?->slug === 'admin');
    }

    /**
     * Check if user has a specific role.
     * @param string $slug Role identifier (e.g., 'admin')
     * @return bool
     */
    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    /**
     * Return user abilities based on role.
     * Useful for:
     * - API token scopes
     * - Frontend permission toggles
     * - Conditional UI rendering
     *
     * @return array<string, bool>
     */
    public function abilities()
    {
        $slug = $this->role?->slug;
        return[
            // Null-safe operator (?->) prevents crashes if a user has no role
            'ADMIN' => $slug === 'admin',
            'GYM_MANAGER' => $slug === 'gym_manager',
            'TRAINER' => $slug === 'trainer',
            'STAFF' => $slug === 'staff',
            'USER' => $slug === 'user',
        ];
    }
}
