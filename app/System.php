<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\System
 *
 * @property int $id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|System newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|System newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|System query()
 * @method static \Illuminate\Database\Eloquent\Builder|System whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class System extends Model
{
    protected $guarded = [];
    public static function getStatusName($statusId): string
    {
        switch ($statusId) {
            case 0:
                return 'disabled';
            case 2:
                return 'only_admins';
            default:
                return 'enabled';
        }
    }

    public static function getStatusColor($status): string
    {
        switch ($status) {
            case 'disabled':
                return 'bg-danger';
            case 'only_admins':
                return 'bg-warning';
            default:
                return 'bg-success';
        }
    }


}
