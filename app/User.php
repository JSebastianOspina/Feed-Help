<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

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

    public function admittedOnMaintenance(): bool
    {
        return ($this->isAdmin() || $this->isOwner());
    }

    public function isAdmin(): bool
    {
        return $this->role === 1;
    }

    public function isOwner(): bool
    {
        return $this->role === 2;
    }

    public function decks()
    {
        return $this->belongsToMany(Deck::class);
    }

    public function twitterAccounts()
    {
        return $this->hasMany(TwitterAccount::class);
    }

    public function getDeckInfo($deckId): array
    {
        $deckUser = DB::table('deck_user')
            ->where('user_id', '=', $this->id)
            ->where('deck_id', '=', $deckId)
            ->first();

        if ($deckUser === null) {
            return [
                'role' => null,
                'hasPermission' => false,
            ];
        }
        return [
            'role' => $deckUser->role,
            'hasPermission' => true,
        ];


    }

}
