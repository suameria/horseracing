<?php

namespace App\Repositories\Race;

interface RaceRepositoryInterface
{
    public function create(array $data);

    public function updateOrCreate(array $attributes, array $data);

    public function updateRegistStatusByRaceKey($raceKey);

    public function getHorseKey();

    public function getJockeyKey();

    public function getTrainerKey();
}