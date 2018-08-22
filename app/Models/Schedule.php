<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'id',
        'calendar_id',
        'race',
        'date',
        'title',
        'detail_1',
        'detail_2',
        'detail_3',
        'detail_4',
        'detail_5',
        'detail_6',
        'status',
        'race_key',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'date'
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function races()
    {
        return $this->hasMany(Race::class);
    }

}
