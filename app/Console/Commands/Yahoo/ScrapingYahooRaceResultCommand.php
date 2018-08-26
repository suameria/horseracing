<?php

namespace App\Console\Commands\Yahoo;

use Illuminate\Console\Command;

use Goutte\Client;

use App\Services\Scraping\Yahoo\YahooServiceInterface;
use App\Services\Scraping\Yahoo\Schedule\ScheduleServiceInterface;
use App\Services\Scraping\Yahoo\Race\RaceServiceInterface;
use App\Services\Scraping\Yahoo\Refund\RefundServiceInterface;
use App\Services\Scraping\Yahoo\Lap\LapServiceInterface;
use App\Services\Scraping\Yahoo\Corner\CornerServiceInterface;

class ScrapingYahooRaceResultCommand extends Command
{
    protected $signature = 'yahoo:race-result';
    protected $description = 'Get yahoo keiba schedule race refund data by scraping';

    protected $client;
    protected $yahooService;
    protected $scheduleService;
    protected $raceService;
    protected $refundService;
    protected $lapService;
    protected $cornerService;

    public function __construct(
        Client                   $client,
        YahooServiceInterface    $yahooService,
        ScheduleServiceInterface $scheduleService,
        RaceServiceInterface     $raceService,
        RefundServiceInterface   $refundService,
        LapServiceInterface      $lapService,
        CornerServiceInterface   $cornerService
    ) {
        parent::__construct();
        $this->client          = $client;
        $this->yahooService    = $yahooService;
        $this->scheduleService = $scheduleService;
        $this->raceService     = $raceService;
        $this->refundService   = $refundService;
        $this->lapService      = $lapService;
        $this->cornerService   = $cornerService;
    }

    public function handle()
    {
        $lastRaceYear = $this->scheduleService->getLastRaceYear();
        $lastRaceKey  = $this->scheduleService->getLastRaceKey();
        $lastPlaceNo  = (int)substr($lastRaceKey, 2, 2) ?: null;

        foreach (range(config('yahoo.years.from'), config('yahoo.years.to')) as $year) {
            if (isset($lastRaceYear) && $year < $lastRaceYear) continue;

            foreach (array_keys(config('yahoo.places')) as $place) {
                if (isset($lastPlaceNo) && $place < $lastPlaceNo) continue;

                $schedule = config('yahoo.urls.schedule') . $year . '/?place=' . $place;
                $urls     = $this->client->request('GET', $schedule)->filter('.scheLs .wsLB a')->extract('href');
                if (empty($urls)) continue;
                $raceUrls = $this->yahooService->createRaceUrls($urls);

                if (isset($lastRaceKey)) $raceUrls = $this->yahooService->skipRaceUrls($raceUrls, $lastRaceKey);

                if (isset($lastRaceYear, $lastRaceKey, $lastPlaceNo)) {
                    unset($lastRaceYear, $lastRaceKey, $lastPlaceNo);
                }

                foreach ($raceUrls as $raceUrl) {

                    usleep(100000);

                    $this->comment("{$raceUrl}");

                    $crawler = $this->client->request('GET', $raceUrl);
                    $raceKey = getNumber($raceUrl);

                    $data['schedule'] = $this->scheduleService->getSchedule($crawler, $raceKey);
                    if ($data['schedule']['race'] === null) {
                        continue;
                    }

                    $data['schedule'] = array_merge($data['schedule'], ['status' => config('constants.status.schedule.after')]);
                    $data['races']    = $this->raceService->getRaceResult($crawler, $raceKey, config('constants.status.race.result'));
                    $data['refunds']  = $this->refundService->getRefund($crawler, $raceKey);
                    $data['lap']      = $this->lapService->getLap($crawler, $raceKey);
                    $data['corner']   = $this->cornerService->getCorner($crawler, $raceKey);

                    $result = $this->yahooService->updateOrCreateRaceResult($data);
                    if (!$result) {
                        $this->error('ERROR:Stop scraping!');
                        exit;
                    }

                    $this->question("{$data['schedule']['date']} {$data['schedule']['race']}R {$data['schedule']['title']}");
                }
            }
        }
    }
}
