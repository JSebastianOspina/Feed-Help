<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Deck;

//Añadimos libreria

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isOwner(): bool
    {
        return $this->role === 2;
    }

    public function isAdmin(): bool
    {
        return $this->role === 1;
    }

    public function admittedOnMaintenance(): bool
    {
        return ($this->isAdmin() || $this->isOwner());
    }

    public function decks()
    {
        return $this->belongsToMany(Deck::class);
    }

}
