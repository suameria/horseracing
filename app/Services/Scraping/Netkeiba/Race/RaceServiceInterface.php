<?php

namespace App\Services\Scraping\Netkeiba\Race;

interface RaceServiceInterface
{
    public function getRaceResult($crawler, $raceKey, $raceStatus);
}