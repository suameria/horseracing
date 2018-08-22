<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'calendars';

    protected $fillable = [
        'id',
        'date',
        'grade',
        'title',
        'list_key',
        'race_key',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'date'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function raceOrderOfFinishThree()
    {
        return $this->hasMany(Race::class, 'race_key', 'race_key')->where('order_of_finish', '>', 0)->orderBy('order_of_finish')->take(3)->get();
    }
}
