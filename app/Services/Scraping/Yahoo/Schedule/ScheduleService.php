<?php

namespace App\Services\Scraping\Yahoo\Schedule;

use App\Repositories\Schedule\ScheduleRepositoryInterface;

class ScheduleService implements ScheduleServiceInterface
{
    protected $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getSchedule($crawler, $raceKey)
    {
        $data = $crawler->filter('div#raceTit tr')->each(function($element) use($raceKey){

            $raceDetail = $this->getRaceDetail($element->filter('p#raceTitMeta'));

            return [
                'race'     => (int)trim($element->filter('td#raceNo')->text()),
                'date'     => $this->getScheduleDate(trim($element->filter('p#raceTitDay')->text())),
                'title'    => trim($element->filter('td .fntB')->text()),
                'detail_1' => $raceDetail['detail_1'], // コース
                'detail_2' => $raceDetail['detail_2'], // 天気
                'detail_3' => $raceDetail['detail_3'], // 馬場状態
                'detail_4' => $raceDetail['detail_4'], // 条件1 ex)サラ系障害3歳以上
                'detail_5' => $raceDetail['detail_5'], // 条件2 ex)オープン （混合） 別定
                'detail_6' => $raceDetail['detail_6'], // 本賞金
                'status'   => config('constants.status.schedule.after'),
                'race_key' => $raceKey,
            ];
        });

        if (empty($data[0])) {
            $data[0] = [
                'race'     => null,
                'date'     => null,
                'title'    => null,
                'detail_1' => null, // コース
                'detail_2' => null, // 天気
                'detail_3' => null, // 馬場状態
                'detail_4' => null, // 条件1 ex)サラ系障害3歳以上
                'detail_5' => null, // 条件2 ex)オープン （混合） 別定
                'detail_6' => null, // 本賞金
                'status'   => config('constants.status.schedule.after'),
                'race_key' => $raceKey,
            ];
        }

        return $data[0];
    }

    private function getRaceDetail($raceDetail)
    {
        $explode = explode('|', trim($raceDetail->text()));

        $detail['detail_1'] = trim($explode[0]);
        $detail['detail_2'] = $raceDetail->filter('img')->eq(0)->attr('alt');
        $detail['detail_3'] = $raceDetail->filter('img')->eq(1)->attr('alt');
        $detail['detail_4'] = trim($explode[3]);
        $detail['detail_5'] = trim($explode[4]);
        $detail['detail_6'] = trim($explode[5]);

        return $detail;
    }

    private function getScheduleDate($raceDay)
    {
        $explode = explode('|', $raceDay);
        $exYear  = explode('年', $explode[0]);
        $exMonth = explode('月', $exYear[1]);
        $exDay   = explode('日', $exMonth[1]);
        $exTime  = '00:00:00';
        if (strpos($raceDay, ':') !== false) {
            $exTime = explode(':', trim($explode[2]));
            $exTime = $exTime[0] . ':' . substr($exTime[1], 0, 2) . ':00';
        }
        return $exYear[0] . '-' . $exMonth[0] . '-' . $exDay[0] . ' ' . $exTime;
    }

    public function getLastRaceKey()
    {
        if (isset($this->scheduleRepository->getLastRaceKey()->race_key)) {
            return $this->scheduleRepository->getLastRaceKey()->race_key;
        }
        return null;
    }

    public function getLastRaceYear()
    {
        if (isset($this->scheduleRepository->getLastRaceDate()->date)) {
            return $this->scheduleRepository->getLastRaceDate()->date->format('Y');
        }
        return null;
    }
}
