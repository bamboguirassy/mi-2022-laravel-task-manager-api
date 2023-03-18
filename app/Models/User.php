<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the taches for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taches(): HasMany
    {
        return $this->hasMany(Tache::class);
    }

    /**
     * Get all of the tacheTerminees for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tacheTerminees(): HasMany
    {
        return $this->hasMany(Tache::class)->whereDone(true);
    }

    /**
     * Get all of the tacheEnCours for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tacheEnCours(): HasMany
    {
        return $this->hasMany(Tache::class)->whereDone(false);
    }
}
