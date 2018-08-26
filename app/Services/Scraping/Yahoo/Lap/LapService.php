<?php

namespace App\Services\Scraping\Yahoo\Lap;

class LapService implements LapServiceInterface
{
    public function __construct()
    {

    }

    public function getLap($crawler, $raceKey)
    {
        $lap = $crawler->filter('div.layoutCol2R tr')->each(function($element) {

            $col1 = null;
            $col2 = null;
            $col3 = null;
            $col4 = null;

            if (count($element->filter('td')->eq(0))) {
                $col1 = trim($element->filter('td')->eq(0)->text());
            }

            if (count($element->filter('td')->eq(1))) {
                $col2 = trim($element->filter('td')->eq(1)->text());
            }

            $implodeDash = $this->implodeDash($col1, $col2);

            if (count($element->filter('td')->eq(2))) {
                $col3 = trim($element->filter('td')->eq(2)->text());
            }

            $implodeDash = $this->implodeDash($implodeDash, $col3);

            if (count($element->filter('td')->eq(3))) {
                $col4 = trim($element->filter('td')->eq(3)->text());
            }

            $implodeDash = $this->implodeDash($implodeDash, $col4);
            if ($implodeDash === '---') {
                $implodeDash = null;
            }

            return $implodeDash;
        });

        $data = [
            'time'     => implode('-', array_values(array_filter($lap))),
            'race_key' => $raceKey,
        ];

        return $data;
    }

    private function implodeDash($left, $right)
    {
        if ($right !== ' ') {
            $left = $left . '-' . $right;
        }

        return $left;
    }



}
