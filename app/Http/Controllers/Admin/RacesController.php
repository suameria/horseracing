<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Race;
use App\Models\Schedule;

class RacesController extends Controller
{
    protected $calendar;
    protected $race;
    protected $schedule;

    public function __construct(Calendar $calendar, Race $race, Schedule $schedule)
    {
        $this->calendar = $calendar;
        $this->race     = $race;
        $this->schedule = $schedule;
    }

    /**
     * 出馬表
     *
     * @param  string $raceKey
     * @return Illuminate\Http\Response
     */
    public function denma($raceKey)
    {
        $schedule  = $this->schedule->where('race_key', $raceKey)->firstOrFail();
        $calendars = $this->calendar->whereDate('date', $schedule->date->format('Y-m-d'))->get();

        $hasHorseNum = config('constants.enabled');
        $races       = $schedule->races()->whereNotNull('horse_number')->orderBy('horse_number')->get();

        // 馬番が存在しない
        if ($races->count() === 0) {
            $hasHorseNum = config('constants.disabled');
            $races       = $schedule->races()->get();
        }

        return view('admin.races.denma', compact('raceKey', 'schedule', 'calendars', 'hasHorseNum', 'races'));
    }

    /**
     * レース結果
     *
     * @param  string $raceKey
     * @return Illuminate\Http\Response
     */
    public function result($raceKey)
    {
        $schedule = Schedule::where('race_key', $raceKey)->where('status', config('constants.status.schedule.after'))->first();
        if (!$schedule) return redirect()->route('admin.races.denma', $raceKey);

        $calendars = Calendar::whereDate('date', $schedule->date->format('Y-m-d'))->get();
        $races = $schedule->races()->where('status', config('constants.status.race.result'))->orderByRaw('order_of_finish = 0 asc')->orderby('order_of_finish')->get();
        return view('admin.races.result', compact('schedule', 'calendars', 'races', 'raceKey'));
    }

    /**
     * 馬柱5走
     *
     * @param  string $raceKey
     * @return Illuminate\Http\Response
     */
    public function pillar5($raceKey)
    {
        $races       = $this->race->where('race_key', $raceKey)->whereNotNull('horse_number')->get();
        $hasHorseNum = ($races->count()) ? config('constants.enabled') : config('constants.disabled');

        $schedule = $this->schedule->with([
            'races' => function ($query) use ($hasHorseNum) {
                if ($hasHorseNum) {
                    $query->select([
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
                    ])->whereNotNull('horse_number')->orderBy('horse_number');
                }
            },
            'races.pillar5' => function ($query) use ($raceKey) {
                $query->whereNotIn('races.race_key', [$raceKey]);
            },
            'races.pillar5.preRace:id,time,passing,three_furlong,horse_weight,change_weight,sign,horse_name,race_key,status,order_of_finish',
            'races.horse',
            'races.horse.father:id,name,horse_key,m_horse_key,f_horse_key',
            'races.horse.mother:id,name,horse_key,m_horse_key,f_horse_key',
            'races.horse.mother.father:id,name,horse_key,m_horse_key,f_horse_key',
            'races.trainer:id,training_center,trainer_key',
        ])->where('race_key', $raceKey)->first();

        $calendars = Calendar::whereDate('date', $schedule->date->format('Y-m-d'))->get(['list_key', 'race_key']);

        return view('admin.races.pillar', compact('raceKey', 'hasHorseNum', 'schedule', 'calendars'));
    }
}
