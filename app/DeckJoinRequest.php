<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeckJoinRequest extends Model
{
    protected $guarded = [];

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
}
