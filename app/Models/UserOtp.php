<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    /**
     * Mass assignable attributes.
     * These fields can be safely assigned via create() or update().
     * @var list<string>
     */
    protected $fillable = [
        'otp',
        'expires_at',
        'user_id',
    ] ;


    /**
     * Attribute casting rules.
     * Ensures proper data typing and automatic transformations.
     * @return array<string, string>
     */
    protected $casts = [
        'expires_at'=> 'datetime',
    ] ;

    public function isExpired(){
        return $this->expires_at->isPast();
    }
}
