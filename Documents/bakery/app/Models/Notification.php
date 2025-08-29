<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'tbl_notifications';

    protected $fillable = [
        'type',
        'message',
        'is_read'
    ];
}
