<?php

namespace App\Console\Commands\Yahoo;

use Illuminate\Console\Command;

use Goutte\Client;
use Carbon\Carbon;

use App\Services\Scraping\Yahoo\YahooServiceInterface;
use App\Services\Scraping\Yahoo\Schedule\ScheduleServiceInterface;
use App\Services\Scraping\Yahoo\Race\RaceServiceInterface;
use App\Services\Scraping\Yahoo\Refund\RefundServiceInterface;
use App\Services\Scraping\Yahoo\Lap\LapServiceInterface;
use App\Services\Scraping\Yahoo\Corner\CornerServiceInterface;
use App\Services\Scraping\Netkeiba\NetkeibaServiceInterface;
use App\Services\Scraping\Netkeiba\Race\RaceServiceInterface as NetkeibaRaceService;
use App\Repositories\Schedule\ScheduleRepositoryInterface;

class RaceScheduleCommand extends Command
{
    protected $signature   = 'yahoo:race-schedule {year?} {month?}';
    protected $description = 'Get yahoo keiba race schedule by scraping';

    protected $client;
    protected $yahooService;
    protected $scheduleService;
    protected $raceService;
    protected $refundService;
    protected $lapService;
    protected $cornerService;
    protected $netkeibaService;
    protected $netkeibaRaceService;
    protected $scheduleRepository;
    protected $carbon;

    public function __construct(
        Client                      $client,
        YahooServiceInterface       $yahooService,
        ScheduleServiceInterface    $scheduleService,
        RaceServiceInterface        $raceService,
        RefundServiceInterface      $refundService,
        LapServiceInterface         $lapService,
        CornerServiceInterface      $cornerService,
        NetkeibaServiceInterface    $netkeibaService,
        NetkeibaRaceService         $netkeibaRaceService,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        parent::__construct();
        $this->client              = $client;
        $this->yahooService        = $yahooService;
        $this->scheduleService     = $scheduleService;
        $this->raceService         = $raceService;
        $this->refundService       = $refundService;
        $this->lapService          = $lapService;
        $this->cornerService       = $cornerService;
        $this->netkeibaService     = $netkeibaService;
        $this->netkeibaRaceService = $netkeibaRaceService;
        $this->scheduleRepository  = $scheduleRepository;
        $this->carbon              = new Carbon();
    }

    public function handle()
    {
        $year  = $this->carbon->format('Y');
        $month = $this->carbon->format('n');
        if ($this->checkArgument()) {
            $year  = $this->argument('year');
            $month = $this->argument('month');
        }

        $schedule = config('yahoo.urls.schedule') . $year . '/?month=' . $month;
        $urls     = $this->client->request('GET', $schedule)->filter('.scheLs .wsLB a')->extract('href');
        $raceUrls = $this->yahooService->createRaceUrls($urls);

        foreach ($raceUrls as $raceUrl) {

            usleep(100000);
            $this->comment("{$raceUrl}");

            $raceKey          = getNumber($raceUrl);
            $crawler          = $this->client->request('GET', $raceUrl);
            $data['schedule'] = $this->scheduleService->getSchedule($crawler, $raceKey);
            if ($data['schedule']['race'] === null) continue;

            $currentUrl = $this->client->getHistory()->current()->getUri();
            $raceStatus = config('constants.status.race.result'); // 出走確定
            if ($raceUrl !== $currentUrl) {

                $data['schedule'] = array_merge($data['schedule'], ['status' => config('constants.status.schedule.before')]);

                $raceStatus = config('constants.status.race.regist'); // 特別登録(regist)
                if (strpos($currentUrl, 'denma') !== false) {
                    $raceStatus = config('constants.status.race.denma');
                }

                $raceUrl = config('netkeiba.urls.race') . '?pid=race_old&id=p' .  substr($this->carbon->format('Y'), 0, 2) . $raceKey;
                $crawler = $this->client->request('GET', $raceUrl);

                $this->comment("Change {$raceUrl}");

                $data['races'] = $this->netkeibaRaceService->getRaceResult($crawler, $raceKey, $raceStatus);

                $result = $this->netkeibaService->updateOrCreateRaceResult($data);
                if (!$result) {
                    $this->error('ERROR:Stop scraping!(netkeiba)');
                    exit;
                }

                $this->question("DATA {$data['schedule']['date']} {$data['schedule']['race']}R {$data['schedule']['title']}");

            } else {
                $data['schedule'] = array_merge($data['schedule'], ['status' => config('constants.status.schedule.after')]);
                $data['races']    = $this->raceService->getRaceResult($crawler, $raceKey, $raceStatus);
                $data['refunds']  = $this->refundService->getRefund($crawler, $raceKey);
                $data['lap']      = $this->lapService->getLap($crawler, $raceKey);
                $data['corner']   = $this->cornerService->getCorner($crawler, $raceKey);

                $result = $this->yahooService->updateOrCreateRaceResult($data);
                if (!$result) {
                    $this->error('ERROR:Stop scraping!');
                    exit;
                }

                $this->question("INSERT DATA: {$data['schedule']['date']} {$data['schedule']['race']}R {$data['schedule']['title']}");
            }
        }
    }

    private function checkArgument()
    {
        if ($this->argument("year")) {
            // yearチェック
            if (!preg_match('/^([1-9][0-9]{3})$/', $this->argument("year"))) {
                $this->error("年の指定が不正な値ため停止");
                exit;
            }
            // monthがnullでNG
            if (!$this->argument("month")) {
                $this->error("月の指定が無いため停止");
                exit;
            }
            // monthチェック
            if (!preg_match('/^([1-9]{1}|1[0-2]{1})$/', $this->argument("month"))) {
                $this->error("月の指定が不正な値ため停止");
                exit;
            }
            return true;
        }
        return false;

    }
}
