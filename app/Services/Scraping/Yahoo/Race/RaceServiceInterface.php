<?php

namespace App\Services\Scraping\Yahoo\Race;

interface RaceServiceInterface
{
    public function getRaceResult($crawler, $raceKey, $raceStatus);

    public function getHorseKey();

    public function getJockeyKey();

    public function getTrainerKey();
}