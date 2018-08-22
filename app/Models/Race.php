<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    protected $table = 'races';

    protected $fillable = [
        'id',
        'schedule_id',
        'order_of_finish',
        'post_position',
        'horse_number',
        'horse_name',
        'horse_sex',
        'horse_age',
        'horse_weight',
        'sign',
        'change_weight',
        'is_blinker',
        'time',
        'margin_disp',
        'passing',
        'three_furlong',
        'jockey_name',
        'weight',
        'favorite',
        'odds',
        'trainer_name',
        'status',
        'race_key',
        'horse_key',
        'jockey_key',
        'trainer_key',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'date'
    ];

    public function horse()
    {
        return $this->hasOne(Horse::class, 'horse_key', 'horse_key');
    }

    public function jockey()
    {
        return $this->hasOne(Jockey::class, 'jockey_key', 'jockey_key');
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'trainer_key', 'trainer_key');
    }

    public function pillar5()
    {
        return $this->hasMany(Race::class, 'horse_key', 'horse_key')
                ->join('schedules', function ($join) {
                    $join->on('schedules.id', '=', 'races.schedule_id');
//                        ->where('races.status', config('constants.status.race.result'))
//                        ->where('schedules.status', config('constants.status.schedule.after'));
                })
                ->whereNotNull('order_of_finish')
                ->orderBy('schedules.date', 'desc');
    }

    public function preRace()
    {
        return $this->hasMany(Race::class, 'race_key', 'race_key')
                ->where('races.status', config('constants.status.race.result'))
                ->where('order_of_finish', '>', 0)
                ->orderBy('order_of_finish');
    }
}
