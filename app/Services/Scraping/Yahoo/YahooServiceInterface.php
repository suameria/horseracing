<?php

namespace App\Services\Scraping\Yahoo;

interface YahooServiceInterface
{
    public function createRaceUrls($urls);

    public function skipRaceUrls($raceUrls, $lastRaceKey);

    public function updateOrCreateRaceResult($data);
}