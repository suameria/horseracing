<?php

namespace App\Repositories\Schedule;

interface ScheduleRepositoryInterface
{
    public function create(array $data);

    public function updateOrCreate(array $attributes, array $data);

    public function getLastRaceKey();

    public function getLastRaceDate();

    public function getStatus($raceKey);
}