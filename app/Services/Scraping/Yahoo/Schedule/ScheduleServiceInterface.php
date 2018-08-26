<?php

namespace App\Services\Scraping\Yahoo\Schedule;

interface ScheduleServiceInterface
{
    public function getSchedule($crawler, $raceKey);

    public function getLastRaceKey();

    public function getLastRaceYear();
}