<?php

namespace App\Repositories\Race;

use App\Models\Race;

class RaceRepository implements RaceRepositoryInterface
{
    protected $race;

    public function __construct(Race $race)
    {
        $this->race = $race;
    }

    public function create(array $data)
    {
        return $this->race->create($data);
    }

    public function updateOrCreate(array $attributes, array $data)
    {
        return $this->race->updateOrCreate($attributes, $data);
    }

    public function updateRegistStatusByRaceKey($raceKey)
    {
        $registStatus = ['status' => config('constants.status.race.regist')];
        return $this->race->where('race_key', $raceKey)->update($registStatus);
    }

    public function getHorseKey()
    {
        return $this->race->groupBy('horse_key')->orderBy('horse_key', 'asc')->get(['horse_key']);
    }

    public function getJockeyKey()
    {
        return $this->race->groupBy('jockey_key')->orderBy('jockey_key', 'asc')->get(['jockey_key']);
    }

    public function getTrainerKey()
    {
        return $this->race->groupBy('trainer_key')->orderBy('trainer_key', 'asc')->get(['trainer_key']);
    }
}
