<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class Kullanici extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'kullanicilar';

    protected $fillable = [
        'ad_soyad',
        'email',
        'sifre',
        'rol'
    ];

    protected $hidden = [
        'sifre',
        'remember_token',
    ];

    /**
     * Get the password field name for authentication
     */
    public function getAuthPasswordName()
    {
        return 'sifre';
    }

    /**
     * Automatically hash password when setting
     */
    public function setSifreAttribute($value)
    {
        $this->attributes['sifre'] = Hash::make($value);
    }

    /**
     * Override the default password field for authentication
     */
    public function getAuthPassword()
    {
        return $this->sifre;
    }

    /**
     * Get the password attribute name for JWT
     */
    public function getJWTPassword()
    {
        return $this->sifre;
    }

    /**
     * Override password attribute for Laravel auth
     */
    public function getPasswordAttribute()
    {
        return $this->sifre;
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
