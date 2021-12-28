<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Deck
 *
 * @property int $id
 * @property string $name
 * @property string|null $icon
 * @property string $owner_name
 * @property int $rt_number
 * @property int $delete_minutes
 * @property string $description
 * @property int $followers
 * @property string|null $whatsapp_group_url
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TwitterAccount[] $twitterAccounts
 * @property-read int|null $twitter_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Deck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deck query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereDeleteMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereOwnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereRtNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deck whereWhatsappGroupUrl($value)
 * @mixin \Eloquent
 */
class Deck extends Model
{
    protected $fillable = ['icon', 'name', 'owner_name', 'rt_number', 'delete_minutes', 'followers', 'enabled', 'isPublic', 'telegram_username', 'whatsapp_group_url','min_followers'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    public function twitterAccounts()
    {
        return $this->hasMany(TwitterAccount::class);

    }

    public function apis()
    {
        return $this->hasMany(Api::class);

    }
}
