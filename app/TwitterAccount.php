<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TwitterAccount
 *
 * @property int $id
 * @property string $username
 * @property int $followers
 * @property string $image_url
 * @property int $deck_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereDeckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterAccount whereUsername($value)
 * @mixin \Eloquent
 */
class TwitterAccount extends Model
{
    protected $fillable = ['username', 'followers', 'image_url', 'deck_id', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }

    public function twitterAccountApi()
    {
        return $this->hasMany(TwitterAccountApi::class);
    }
}
