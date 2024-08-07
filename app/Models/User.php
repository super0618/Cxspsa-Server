<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $table = 'cxs_user';

    public function getJWTIdentifier()
    {
        // Return the primary key of the user
        return (string) $this->getKey();
    }

    // Define the method to return an array of custom claims
    public function getJWTCustomClaims()
    {
        // Return an empty array for default claims
        return [];
    }
}
