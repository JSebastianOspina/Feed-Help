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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
