<?php

namespace App\Services\Scraping\Yahoo\Lap;

interface LapServiceInterface
{
    public function getLap($crawler, $raceKey);
}