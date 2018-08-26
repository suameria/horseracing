<?php

namespace App\Services\Scraping\Netkeiba;

use Carbon\Carbon;

use App\Repositories\Calendar\CalendarRepositoryInterface;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Repositories\Race\RaceRepositoryInterface;

use DB;
use Log;

class NetkeibaService implements NetkeibaServiceInterface
{
    protected $calendarRepository;
    protected $scheduleRepository;
    protected $raceRepository;
    protected $carbon;

    public function __construct(
        CalendarRepositoryInterface $calendarRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        RaceRepositoryInterface     $raceRepository
    ) {
        $this->calendarRepository = $calendarRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->raceRepository     = $raceRepository;
        $this->carbon             = new Carbon();
    }

    public function updateOrCreateRaceResult($data)
    {
        DB::beginTransaction();
        try {
            $raceKey = $data['schedule']['race_key'];

            $listKey  = substr($raceKey, 0, 8);
            $calendar = $this->calendarRepository->getCalendarByListKey($listKey);
            $data['schedule']['calendar_id'] = $calendar->id;

            // schedule登録
            $schedule = $this->scheduleRepository->updateOrCreate(['race_key' => $raceKey], $data['schedule']);

            // いったん特別登録のステータスにリセット
            $this->raceRepository->updateRegistStatusByRaceKey($raceKey);

            foreach ($data['races'] as $race) {
                $where = [
                    'race_key'  => $race['race_key'],
                    'horse_key' => $race['horse_key'],
                ];
                $this->raceRepository->updateOrCreate($where, array_merge($race, ['schedule_id' => $schedule->id]));
            }

            DB::commit();
            return $data;
        } catch (\Exception $e){
            DB::rollBack();
            Log::error(get_class() . ' Exception Error.');
            return false;
        }
    }
}
