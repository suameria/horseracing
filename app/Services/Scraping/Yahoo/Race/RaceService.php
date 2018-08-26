<?php

namespace App\Services\Scraping\Yahoo\Race;

use App\Repositories\Race\RaceRepositoryInterface;

class RaceService implements RaceServiceInterface
{
    protected $raceRepository;

    public function __construct(
        RaceRepositoryInterface $raceRepository
    ) {
        $this->raceRepository = $raceRepository;
    }

    public function getRaceResult($crawler, $raceKey, $raceStatus)
    {
        $data = $crawler->filter('table#raceScore tbody tr')->each(function($element) use($raceKey, $raceStatus){

            $horse = $this->getHorseDetail(trim($element->filter('td.fntN .scdItem')->text()));

            return [
                'order_of_finish' => (int)trim($element->filter('td.txC')->eq(0)->text()),
                'post_position'   => (int)trim($element->filter('td.txC')->eq(1)->text()),
                'horse_number'    => (int)trim($element->filter('td.txC')->eq(2)->text()),
                'horse_name'      => trim($element->filter('td.fntN a')->text()),
                'horse_sex'       => $horse['horse_sex'],
                'horse_age'       => (int)$horse['horse_age'],
                'horse_weight'    => (int)$horse['horse_weight'],
                'sign'            => $horse['sign'],
                'change_weight'   => (int)$horse['change_weight'],
                'is_blinker'      => (int)$horse['is_blinker'],
                'time'            => getLessThanLeftValue(trim($element->filter('td')->eq(4)->html())) ?: null,
                'margin_disp'     => trim($element->filter('td')->eq(4)->filter('.scdItem')->text()) ?: null,
                'passing'         => getLessThanLeftValue(trim($element->filter('td')->eq(5)->html())) ?: null,
                'three_furlong'   => (float)trim($element->filter('td')->eq(5)->filter('.scdItem')->text()) ?: null,
                'jockey_name'     => trim($element->filter('td.txL a')->eq(1)->text()),
                'weight'          => (float)trim($element->filter('td')->eq(6)->filter('.scdItem')->text()),
                'favorite'        => (int)trim(getLessThanLeftValue(trim($element->filter('td')->eq(7)->html()))) ?: null,
                'odds'            => (float)removeBrackets(trim($element->filter('td')->eq(7)->filter('.scdItem')->text())) ?: null,
                'trainer_name'    => trim($element->filter('td.txL a')->eq(2)->text()),
                'status'          => $raceStatus,
                'race_key'        => $raceKey,
                'horse_key'       => getNumber(trim($element->filter('td.fntN a')->attr('href'))),
                'jockey_key'      => getNumber(trim($element->filter('td.txL a')->eq(1)->attr('href'))),
                'trainer_key'     => getNumber(trim($element->filter('td.txL a')->eq(2)->attr('href'))),
            ];
        });

        return $data;
    }

    private function getHorseDetail($data)
    {
        // 性別、年齢
        $explode            = explode('/', $data);
        $horse['horse_sex'] = mb_substr($explode[0], 0, 1);
        $horse['horse_age'] = mb_substr($explode[0], 1);
        if ($horse['horse_sex'] !== '牡' && $horse['horse_sex'] !== '牝') {
            // せん対応
            $horse['horse_sex'] = 'セ';
            $horse['horse_age'] = mb_substr($explode[0], 2);
        }

        // 馬体重
        $explode                = explode('(', $explode[1]);
        $horse['horse_weight']  = trim($explode[0]);
        if ($horse['horse_weight'] === '-') {
            $horse['horse_weight'] = null;
        }

        // 符号、馬体重増減
        $explode       = explode(')', $explode[1]);
        $subSign       = mb_substr($explode[0], 0, 1);
        $horse['sign'] = ($subSign === '+') ? 1 : 0; // 0 (-) 1 (+)
        $horse['change_weight'] = trim(mb_substr($explode[0], 1));
        if ($horse['change_weight'] === '' || $horse['change_weight'] === '-') {
            $horse['sign']         = null;
            $horse['change_weight'] = null;
        }

        // ブリンカー有無
        $horse['is_blinker'] = (strpos($data, 'B')) ? 1 : 0;

        return $horse;
    }

    public function getHorseKey()
    {
        return $this->raceRepository->getHorseKey();
    }

    public function getJockeyKey()
    {
        return $this->raceRepository->getJockeyKey();
    }

    public function getTrainerKey()
    {
        return $this->raceRepository->getTrainerKey();
    }
}
