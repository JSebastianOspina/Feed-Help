<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Record
 *
 * @property int $id
 * @property string $username
 * @property string $tweet_id
 * @property string $success_rt
 * @property string $not_rt_by
 * @property string $extra_info
 * @property int $pending
 * @property int $deck_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Record newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record query()
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereDeckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereExtraInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereNotRtBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record wherePending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereSuccessRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereTweetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereUsername($value)
 * @mixin \Eloquent
 */
class Record extends Model
{
    protected $guarded = [];
}
