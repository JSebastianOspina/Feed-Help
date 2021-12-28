<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitterAccount extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
