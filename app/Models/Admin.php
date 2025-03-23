<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Admin as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = "admins";
    public $timestamps = false;

    protected $fillable = [
        "name",
        "lastName",
        "email",
        "password"
    ];

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
}
