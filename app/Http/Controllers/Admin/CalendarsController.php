<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Carbon\Carbon;
use DB;

class CalendarsController extends Controller
{
    public function index()
    {
        /**
         * @todo
         * year, gradeのrequestsのバリデーション必要かも
         */

        $carbon      = new Carbon();
        $currentYear = $carbon->now()->format('Y');
        $year        = request('year') ?: $carbon->now()->format('Y');
        $month       = request('month') ?: $carbon->now()->format('n');
        $grade       = request('grade') ?: null;

        $query = new Calendar();

        if (request('grade')) {
            $gradeFlip = collect(config('constants.grade'))->flip();
            switch (request('grade')) {
                case 'G123': // 重賞[G1 G2 G3]
                    $query = $query->whereIn('grade', $gradeFlip);
                    break;
                case 'G1': // G1
                    $query = $query->whereIn('grade', [$gradeFlip['G1'],$gradeFlip['JG1']]);
                    break;
                case 'G2': // G2
                    $query = $query->whereIn('grade', [$gradeFlip['G2'],$gradeFlip['JG2']]);
                    break;
                case 'G3': // G3
                    $query = $query->whereIn('grade', [$gradeFlip['G3'],$gradeFlip['JG3']]);
                    break;
                case 'NO': // 重賞以外
                    $query = $query->whereNotIn('grade', $gradeFlip);
                    break;
            }
        }

        $calendars   = $query->whereYear('date', $year)->whereMonth('date', $month)->orderBy('date')->orderBy('race_key')->get();

        return view('admin.calendars.index', compact('calendars', 'year', 'month', 'grade', 'currentYear', 'carbon'));
    }

}
