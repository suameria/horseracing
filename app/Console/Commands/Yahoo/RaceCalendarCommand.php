<?php

namespace App\Console\Commands\Yahoo;

use Illuminate\Console\Command;

use DB;
use Goutte\Client;
use Carbon\Carbon;
use App\Repositories\Calendar\CalendarRepositoryInterface;

class RaceCalendarCommand extends Command
{
    protected $signature   = 'yahoo:race-calendar {all?}';
    protected $description = 'Get yahoo keiba race calendar by scraping *command all=1 1986-current year, 1-12 month';

    protected $client;
    protected $calendarRepository;
    protected $carbon;

    public function __construct(Client $client, CalendarRepositoryInterface $calendarRepository)
    {
        parent::__construct();
        $this->client             = $client;
        $this->calendarRepository = $calendarRepository;
        $this->carbon             = new Carbon();
    }

    public function handle()
    {
        if (($this->argument('all') == 1) ? true : false) {
            foreach (range(config('yahoo.years.from'), config('yahoo.years.to')) as $year) {
                foreach (range(1, 12) as $month) {
                    $this->startScrapingCalendar($year, $month);
                }
            }
        } else {
            $year   = $this->carbon->format('Y');
            $months = [$this->carbon->format('n'), $this->carbon->format('n') + 1];
            foreach ($months as $month) {
                if ($month > 12) break;
                $this->startScrapingCalendar($year, $month);
            }
        }
    }

    private function startScrapingCalendar($year, $month)
    {
        $schedule = config('yahoo.urls.schedule') . $year . '/?month=' . $month;

        $this->progressBar(config('yahoo.sleep'));
        $this->question("URL: {$schedule}");

        $lists = $this->client->request('GET', $schedule)->filter('table.scheLs.mgnBS tbody tr')->each(function($element) use($year, $month) {
            if (count($element->filter('td'))) {
                // list_keyが存在しない場合
                if (!count($element->filter('td')->eq(0)->filter('a'))) {
                    if (count($element->filter('td')->eq(0))) {
                        $openingText = str_replace(PHP_EOL, '', trim($element->filter('td')->eq(0)->text()));
                        if (!$openingText) return; // テキストがなければスキップ処理

                        // 日付
                        $days = explode('日', $openingText)[0];
                        if (preg_match('/^([1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $days)) {
                            $data['date'] = $year.'-'.$month.'-'.$days;
                        } else {
                            $this->error('No days' . $element->filter('td')->eq(0)->text());
                            exit;
                        }

                        // list_key生成 ex) 18060509 ->18(yearNo)+06(placeNo)+05(countNo)+09(dayNo)
                        $preListText = explode('）', $openingText)[1]; // ex) 28日（金）5回中山9日 -> 5回中山9日
                        $preListText = explode('回', $preListText); // ex) 5回中山9日 -> 5 | 中山9日

                        $yearNo  = (string)mb_substr($year, 2, 2);
                        $countNo = (string)str_pad($preListText[0], 2, 0, STR_PAD_LEFT);
                        $placeNo = (string)array_keys(config('constants.places'), mb_substr($preListText[1], 0, 2))[0];
                        $dayNo   = (string)str_pad(preg_replace('/[^0-9]/', '', $preListText[1]), 2, 0, STR_PAD_LEFT);

                        $listKey = $yearNo . $placeNo . $countNo . $dayNo;
                        if (mb_strlen($listKey) === 8) {
                            $data['list_key'] = $listKey;
                        } else {
                            $this->error('mistake creating list key');
                            exit;
                        }

                        return $data;
                    }
                } else {
                    if (strpos(trim($element->filter('td')->eq(0)->filter('a')->attr('href')), 'horse') === false) {
                        $days = explode('日', trim($element->filter('td')->eq(0)->text()))[0];
                        if (preg_match('/^([1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $days)) {
                            $data['date'] = $year.'-'.$month.'-'.$days;
                            $data['list_key'] = getNumber(trim($element->filter('td')->eq(0)->filter('a')->attr('href')));
                            return $data;
                        } else {
                            $this->error('No days');
                            exit;
                        }
                    }
                }
            }
        });

        // null除去
        $calendars = arrayFilterMerge($lists);

        $grades = collect(config('constants.grade'))->flip();

        $lists = $this->client->request('GET', $schedule)->filter('table.scheLs tbody tr')->each(function($element) use($grades) {

            $data = [
                'grade'    => 0,
                'title'    => null,
                'race_key' => null
            ];

            // グレード
            if (count($element->filter('td.wsLB div.spBg'))) {
                switch (trim($element->filter('td.wsLB div.spBg')->text())) {
                    case 'GIII':
                        $data['grade'] = $grades['G3'];
                        break;
                    case 'GII':
                        $data['grade'] = $grades['G2'];
                        break;
                    case 'GI':
                        $data['grade'] = $grades['G1'];
                        break;
                    case 'J・GIII':
                        $data['grade'] = $grades['JG3'];
                        break;
                    case 'J・GII':
                        $data['grade'] = $grades['JG2'];
                        break;
                    case 'J・GI':
                        $data['grade'] = $grades['JG1'];
                        break;
                }
            }

            if (!count($element->filter('td.wsLB a'))) {
                if (count($element->filter('td.wsLB'))) {
                    // レースタイトル
                    $title = str_replace(PHP_EOL, '', trim($element->filter('td.wsLB')->html()));

                    $title = explode('<span', $title);

                    if (strpos($title[0], '</div>') !== false) {
                        $data['title'] = explode('</div>', $title[0])[1];
                    } else {
                        $data['title'] = $title[0];
                    }
                    return $data;
                }
            } else {

                // レースタイトル
                $data['title'] = trim($element->filter('td.wsLB a')->text());

                // レースキー
                if (count($element->filter('td.wsLB a')->attr('href'))) {
                    $data['race_key'] = getNumber($element->filter('td.wsLB a')->attr('href'));
                }
                return $data;
            }
        });

        // null除去
        $mainRaces = arrayFilterMerge($lists);

        foreach ($calendars as $clendarKey => $calendar) {
            foreach ($mainRaces as $mainRaceKey => $mainRace) {
                if ($clendarKey === $mainRaceKey) {
                    $calendars[$clendarKey]['race_key'] = $mainRace['race_key'];
                    $calendars[$clendarKey]['grade']    = $mainRace['grade'];
                    $calendars[$clendarKey]['title']    = $mainRace['title'];
                    continue;
                }
            }
        }

        DB::beginTransaction();
        try {
            foreach ($calendars as $calendarSave) {
                $this->calendarRepository->updateOrCreate(['list_key' => $calendarSave['list_key']], $calendarSave);
                $this->question("INSERT DATA: {$calendarSave['date']} {$calendarSave['title']} | key:{$calendarSave['list_key']}");
            }

            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            $this->error('ERROR:Stop scraping!');
            return;
        }
    }

    private function progressBar($maxSec, $startSentence = "Waiting...", $endSentence = "Starting...")
    {
        echo PHP_EOL;
        $this->comment($startSentence);

        $progress = $this->output->createProgressBar($maxSec);
        $progress->setBarCharacter("■");
        $progress->setEmptyBarCharacter(" ");

        $range = range(1, $maxSec);
        foreach ($range as $i) {
            usleep(500000);
            $progress->advance();
        }
        $progress->finish();

        echo PHP_EOL;
        $this->comment($endSentence);
        echo PHP_EOL;
    }
}
