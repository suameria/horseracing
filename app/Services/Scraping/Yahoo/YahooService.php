<?php

namespace App\Services\Scraping\Yahoo;

use Carbon\Carbon;

use App\Repositories\Calendar\CalendarRepositoryInterface;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Repositories\Race\RaceRepositoryInterface;
use App\Repositories\Refund\RefundRepositoryInterface;
use App\Repositories\Lap\LapRepositoryInterface;
use App\Repositories\Corner\CornerRepositoryInterface;

use DB;
use Log;

class YahooService implements YahooServiceInterface
{
    protected $calendarRepository;
    protected $scheduleRepository;
    protected $raceRepository;
    protected $refundRepository;
    protected $lapRepository;
    protected $cornerRepository;
    protected $carbon;

    public function __construct(
        CalendarRepositoryInterface $calendarRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        RaceRepositoryInterface     $raceRepository,
        RefundRepositoryInterface   $refundRepository,
        LapRepositoryInterface      $lapRepository,
        CornerRepositoryInterface   $cornerRepository
    ) {
        $this->calendarRepository = $calendarRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->raceRepository     = $raceRepository;
        $this->refundRepository   = $refundRepository;
        $this->lapRepository      = $lapRepository;
        $this->cornerRepository   = $cornerRepository;
        $this->carbon             = new Carbon();
    }

    public function createRaceUrls($urls)
    {
        foreach ($urls as $url) {
            $raceKeyRemoveRaceNumber = substr(getNumber($url), 0, 8);
            for ($i = 1; $i <= 12; $i++) {
                if ($i < 10) {
                    $raceUrls[$raceKeyRemoveRaceNumber . '0' . $i] = config('yahoo.urls.race') . $raceKeyRemoveRaceNumber . '0' . $i . '/';
                } else {
                    $raceUrls[$raceKeyRemoveRaceNumber . $i] = config('yahoo.urls.race') . $raceKeyRemoveRaceNumber . $i . '/';
                }
            }
        }

        return $raceUrls;
    }

    public function skipRaceUrls($raceUrls, $lastRaceKey)
    {
        foreach ($raceUrls as $key => $raceUrl) {
            unset($raceUrls[$key]);
            if (strpos($raceUrl, $lastRaceKey) !== false) {
                break;
            }
        }

        return array_values($raceUrls);
    }

    public function updateOrCreateRaceResult($data)
    {
        DB::beginTransaction();
        try {
            $raceKey = $data['schedule']['race_key'];

            $listKey  = substr($raceKey, 0, 8);
            $calendar = $this->calendarRepository->getCalendarByListKey($listKey);
            $data['schedule']['calendar_id'] = $calendar->id;

            $schedule    = $this->scheduleRepository->updateOrCreate(['race_key' => $raceKey], $data['schedule']);
            $scheduleIds = ['schedule_id' => $schedule->id];

            foreach ($data['races'] as $race) {
                $where = [
                    'race_key'  => $race['race_key'],
                    'horse_key' => $race['horse_key'],
                ];
                $this->raceRepository->updateOrCreate($where, array_merge($race, $scheduleIds));
            }

            foreach ($data['refunds'] as $refund) {
                $this->refundRepository->create(array_merge($refund, $scheduleIds));
            }

            $this->lapRepository->create(array_merge($data['lap'], $scheduleIds));

            $this->cornerRepository->create(array_merge($data['corner'], $scheduleIds));

            DB::commit();
            return $data;
        } catch (\Exception $e){
            DB::rollBack();
            Log::error(get_class() . ' Exception Error.');
            return false;
        }
    }


}
