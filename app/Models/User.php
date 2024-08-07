<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticate;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticate implements JWTSubject
{
    protected $table = 'cxs_user';

    public function getJWTIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'email' => $this->email,
        ];
    }
}
