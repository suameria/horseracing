<?php

namespace App\Services\Scraping\Yahoo\Corner;

class CornerService implements CornerServiceInterface
{
    public function __construct()
    {

    }

    public function getCorner($crawler, $raceKey)
    {
        $data = [
            'corner_1' => null,
            'corner_2' => null,
            'corner_3' => null,
            'corner_4' => null,
            'race_key' => $raceKey,
        ];

        $corner = $crawler->filter('table.dataLs.mgnBL tr')->each(function($element) {
            if (count($element->filter('td.txL'))) {
                return $this->getPassing(trim($element->filter('td.txC')->text()), trim($element->filter('td.txL')->text()));
            }
        });

        $corner = collect($corner)->collapse()->toArray();

        return array_merge($data, $corner);
    }


    private function getPassing($corner, $passing)
    {
        switch ($corner) {
            case '1角':
                $data['corner_1'] = $passing;
                break;
            case '2角':
                $data['corner_2'] = $passing;
                break;
            case '3角':
                $data['corner_3'] = $passing;
                break;
            case '4角':
                $data['corner_4'] = $passing;
                break;
        }

        return $data;
    }
}
