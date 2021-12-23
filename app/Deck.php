<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    protected $fillable = ['name', 'owner_name', 'rt_number', 'delete_minutes', 'description','followers'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
