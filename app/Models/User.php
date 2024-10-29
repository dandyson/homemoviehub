<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable,
        Authorizable,
        HasFactory;

    protected $fillable = [
        'auth0',
        'name',
        'email',
        'email_verified',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
    ];

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
