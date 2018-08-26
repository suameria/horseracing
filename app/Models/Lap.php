<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lap extends Model
{
    protected $table = 'laps';

    protected $fillable = [
        'id',
        'schedule_id',
        'time',
        'race_key',
        'created_at',
        'updated_at',
    ];
}
