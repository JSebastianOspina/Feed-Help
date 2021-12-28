<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    protected $fillable = ['icon', 'name', 'owner_name', 'rt_number', 'delete_minutes', 'description', 'followers', 'enabled'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function twitterAccounts()
    {
        return $this->hasMany(TwitterAccount::class);

    }
}
