<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function savings(): HasMany
    {
        return $this->hasMany(Saving::class);
    }
    public function logs(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }
    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }
    public function productComments(): HasMany
    {
        return $this->hasMany(ProductComment::class);
    }
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    public function withdrawal(): HasMany
    {
        return $this->hasMany(Withdraw::class);
    }
    public function newsComments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }
    public function newLikes(): HasMany
    {
        return $this->hasMany(NewsLike::class);
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
