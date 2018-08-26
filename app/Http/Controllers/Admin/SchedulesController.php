<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Calendar;
use Carbon\Carbon;
use DB;

class SchedulesController extends Controller
{
    protected $carbon;
    protected $calendar;
    protected $schedule;

    public function __construct(Carbon $carbon, Calendar $calendar, Schedule $schedule)
    {
        $this->carbon   = $carbon;
        $this->calendar = $calendar;
        $this->schedule = $schedule;
    }

    public function index($listKey)
    {
        $carbon      = $this->carbon;
        $listKeyDate = $this->calendar->where('list_key', $listKey)->first(['date'])->date;
        $lastWeek    = $this->carbon->parse($listKeyDate)->subDay(8)->format('Y-m-d');
        $nextWeek    = $this->carbon->parse($listKeyDate)->addDay(8)->format('Y-m-d');
        $calendars   = $this->calendar->select(DB::raw('max(list_key) as list_key, date'))->whereBetween('date', [$lastWeek, $nextWeek])->groupBy('date')->get();


        foreach (config('constants.places') as $placeNo => $placeName) {
            $schedules[$placeName] = $this->schedule->whereDate('date', $listKeyDate)->where('race_key', 'like', substr(Carbon::now()->format('Y'), 2, 2).$placeNo.'%')->orderBy('race', 'asc')->get();
        }

        return view('admin.schedules.index', compact('listKey', 'listKeyDate', 'dates', 'calendars', 'schedules'));
    }

}
