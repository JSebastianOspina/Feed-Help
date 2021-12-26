<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
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
