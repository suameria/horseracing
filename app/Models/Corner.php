<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corner extends Model
{
    protected $table = 'corners';

    protected $fillable = [
        'id',
        'schedule_id',
        'corner_1',
        'corner_2',
        'corner_3',
        'corner_4',
        'race_key',
        'created_at',
        'updated_at',
    ];
}
