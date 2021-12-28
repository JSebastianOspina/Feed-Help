<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Api
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string $secret
 * @property int $decks_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Api newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Api newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Api query()
 * @method static \Illuminate\Database\Eloquent\Builder|Api whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Api whereDecksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Api whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Api whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Api whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Api whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Api whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Api extends Model
{
    protected $guarded = [];

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
}
