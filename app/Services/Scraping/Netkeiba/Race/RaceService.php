<?php

namespace App\Services\Scraping\Netkeiba\Race;

use Goutte\Client;
use App\Services\Scraping\Netkeiba\Horse\HorseServiceInterface;
use App\Services\Scraping\Netkeiba\Jockey\JockeyServiceInterface;
use App\Services\Scraping\Netkeiba\Trainer\TrainerServiceInterface;
use App\Repositories\Horse\HorseRepositoryInterface;
use App\Repositories\Jockey\JockeyRepositoryInterface;
use App\Repositories\Trainer\TrainerRepositoryInterface;

class RaceService implements RaceServiceInterface
{
    protected $netkeibaStatus; // 枠番と馬番 0:まだ出ていない 1: 出てる(馬体重あり) 2: 出てる(馬体重なし)
    protected $client;
    protected $horseService;
    protected $jockeyService;
    protected $trainerService;
    protected $horseRepository;
    protected $jockeyRepository;
    protected $trainerRepository;

    public function __construct(
        Client                     $client,
        HorseServiceInterface      $horseService,
        JockeyServiceInterface     $jockeyService,
        TrainerServiceInterface    $trainerService,
        HorseRepositoryInterface   $horseRepository,
        JockeyRepositoryInterface  $jockeyRepository,
        TrainerRepositoryInterface $trainerRepository
    ) {
        $this->client            = $client;
        $this->horseService      = $horseService;
        $this->jockeyService     = $jockeyService;
        $this->trainerService    = $trainerService;
        $this->horseRepository   = $horseRepository;
        $this->jockeyRepository  = $jockeyRepository;
        $this->trainerRepository = $trainerRepository;
    }

    public function getRaceResult($crawler, $raceKey, $raceStatus)
    {
        $this->netkeibaStatus = 0;
        if (count($crawler->filter('table.race_table_old tr th')->eq(0)) && strpos(trim($crawler->filter('table.race_table_old tr th')->eq(0)->text()), '印') === false) {
            $this->netkeibaStatus = 2;
            // 馬体重出ている
            if (count($crawler->filter('table.race_table_old tr th')->eq(8)) && strpos(trim($crawler->filter('table.race_table_old tr th')->eq(8)->text()), '馬体重')  !== false) {
                $this->netkeibaStatus = 1;
            }
        }

        $data = $crawler->filter('table.race_table_old tr')->each(function($element) use($raceKey, $raceStatus){

            $race = [
                'order_of_finish' => null, // (int)
                'post_position'   => null, // (int)
                'horse_number'    => null, // (int)
                'horse_name'      => null,
                'horse_sex'       => null,
                'horse_age'       => null, // (int)
                'horse_weight'    => null, // (int)
                'sign'            => null,
                'change_weight'   => null, // (int)
                'is_blinker'      => null, // (int)
                'time'            => null,
                'margin_disp'     => null,
                'passing'         => null,
                'three_furlong'   => null, // (float)
                'jockey_name'     => null,
                'weight'          => null, // (float)
                'favorite'        => null, // (int)
                'odds'            => null, // (float)
                'trainer_name'    => null,
                'status'          => $raceStatus,
                'race_key'        => $raceKey,
                'horse_key'       => null,
                'jockey_key'      => null,
                'trainer_key'     => null,
            ];

            $horseSexAge = config('netkeiba.race_card_eq_number.off.horse_sex_age');
            $weight      = config('netkeiba.race_card_eq_number.off.weight');
            $jockeyKey   = config('netkeiba.race_card_eq_number.off.jockey_key');
            $trainerKey  = config('netkeiba.race_card_eq_number.off.trainer_key');
            $odds        = config('netkeiba.race_card_eq_number.off.odds');
            $favorite    = config('netkeiba.race_card_eq_number.off.favorite');

            // 馬体重あり
            if ($this->netkeibaStatus === 1) {
                $postPosition = config('netkeiba.race_card_eq_number.on.post_position');
                $horseNumber  = config('netkeiba.race_card_eq_number.on.horse_number');
                $horseSexAge  = config('netkeiba.race_card_eq_number.on.horse_sex_age');
                $weight       = config('netkeiba.race_card_eq_number.on.weight');
                $jockeyKey    = config('netkeiba.race_card_eq_number.on.jockey_key');
                $trainerKey   = config('netkeiba.race_card_eq_number.on.trainer_key');
                $horseWeight  = config('netkeiba.race_card_eq_number.on.horse_weight');
                $odds         = config('netkeiba.race_card_eq_number.on.odds');
                $favorite     = config('netkeiba.race_card_eq_number.on.favorite');
            }

            // 馬体重なし
            if ($this->netkeibaStatus === 2) {
                $postPosition = config('netkeiba.race_card_eq_number.on_no_weight.post_position');
                $horseNumber  = config('netkeiba.race_card_eq_number.on_no_weight.horse_number');
                $horseSexAge  = config('netkeiba.race_card_eq_number.on_no_weight.horse_sex_age');
                $weight       = config('netkeiba.race_card_eq_number.on_no_weight.weight');
                $jockeyKey    = config('netkeiba.race_card_eq_number.on_no_weight.jockey_key');
                $trainerKey   = config('netkeiba.race_card_eq_number.on_no_weight.trainer_key');
                $odds         = config('netkeiba.race_card_eq_number.on_no_weight.odds');
                $favorite     = config('netkeiba.race_card_eq_number.on_no_weight.favorite');
            }

            // ホースキー＆馬名
            if (count($element->filter('td.horsename div a'))) {
                $race['horse_key']  = getNumber(trim($element->filter('td.horsename div a')->attr('href')));
                $horse              = $this->horseRepository->getHorseByHorseKey($race['horse_key']);
                $race['horse_name'] = isset($horse->name) ? $horse->name : $this->createHorse($race['horse_key']);
            }

            // 性齢
            if (count($element->filter('td')->eq($horseSexAge))) {
                $sexAge = trim($element->filter('td')->eq($horseSexAge)->text());
                $race['horse_sex'] = mb_substr($sexAge, 0, 1);
                $race['horse_age'] = (int)mb_substr($sexAge, 1);
            }

            // 負担重量
            if (count($element->filter('td')->eq($weight))) {
                $race['weight'] = (int)trim($element->filter('td')->eq($weight)->text());
            }

            // ジョッキーキー＆騎手名
            if (count($element->filter('td')->eq($jockeyKey)->filter('a'))) {
                $race['jockey_key']  = getNumber(trim($element->filter('td')->eq($jockeyKey)->filter('a')->attr('href')));
                $jockey              = $this->jockeyRepository->getJockeyByJockeyKey($race['jockey_key']);
                $race['jockey_name'] = isset($jockey->name) ? $jockey->name : $this->createJockey($race['jockey_key']);
            }

            // トレイナーキー＆調教師名
            if (count($element->filter('td')->eq($trainerKey)->filter('a'))) {
                $race['trainer_key']  = getNumber(trim($element->filter('td')->eq($trainerKey)->filter('a')->attr('href')));
                $trainer              = $this->trainerRepository->getTrainerByTrainerKey($race['trainer_key']);
                $race['trainer_name'] = isset($trainer->name) ? $trainer->name : $this->createTrainer($race['trainer_key']);
            }

            // オッズ
            if (count($element->filter('td')->eq($odds))) {
                $race['odds'] = (float)trim($element->filter('td')->eq($odds)->text());
            }

            // 人気
            if (count($element->filter('td')->eq($favorite))) {
                $race['favorite'] = (int)trim($element->filter('td')->eq($favorite)->text());
            }

            // 枠番出てる(上記共通以外)
            if ($this->netkeibaStatus === 1 || $this->netkeibaStatus === 2) {
                // 枠番
                if (count($element->filter('td')->eq($postPosition))) {
                    $race['post_position'] = (int)trim($element->filter('td')->eq($postPosition)->text());
                }

                // 馬番
                if (count($element->filter('td')->eq($horseNumber))) {
                    $race['horse_number'] = (int)trim($element->filter('td')->eq($horseNumber)->text());
                }

                // 馬体重
                if (!empty($horseWeight) && count($element->filter('td')->eq($horseWeight))) {
                    $horseWeightWithBracket = trim($element->filter('td')->eq($horseWeight)->text());
                    $race['horse_weight']  = (int)explode('(', $horseWeightWithBracket)[0];
                    $changeWeight = explode(')', explode('(', $horseWeightWithBracket)[1])[0];

                    // 符号、馬体重増減
                    $subSign      = mb_substr($changeWeight, 0, 1);
                    $race['sign'] = ($subSign === '+') ? 1 : 0; // 0 (-) 1 (+)
                    $race['change_weight'] = (int)mb_substr($changeWeight, 1);
                    if ($race['change_weight'] === '' || $race['change_weight'] === '-') {
                        $race['sign']          = null;
                        $race['change_weight'] = null;
                    }
                }
            }

            if ($race['horse_name']) return $race;
        });

        return arrayFilterMerge($data);
    }

    private function createHorse($horseKey)
    {
        $horseData = $this->horseService->formatHorseData($this->client->request('GET', config('netkeiba.urls.horse') . $horseKey));

        $fHorseKey = $horseData['f_horse_key'];
        if (empty($this->horseRepository->getHorseByHorseKey($fHorseKey))) $this->horseRepository->create(['horse_key' => $fHorseKey]);

        $mHorseKey = $horseData['m_horse_key'];
        if (empty($this->horseRepository->getHorseByHorseKey($mHorseKey))) $this->horseRepository->create(['horse_key' => $mHorseKey]);

        $this->horseRepository->updateOrCreate($horseKey, $horseData);

        return $horseData['name'];
    }

    private function createJockey($jockeyKey)
    {
        $jockeyData = $this->jockeyService->formatJockeyData($this->client->request('GET', config('netkeiba.urls.jockey') . $jockeyKey));

        $this->jockeyRepository->updateOrCreate($jockeyKey, $jockeyData);

        return $jockeyData['name'];
    }

    private function createTrainer($trainerKey)
    {
        $trainerData = $this->trainerService->formatTrainerData($this->client->request('GET', config('netkeiba.urls.trainer') . $trainerKey));

        $this->trainerRepository->updateOrCreate($trainerKey, $trainerData);

        return $trainerData['name'];
    }
}
