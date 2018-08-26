<?php

namespace App\Repositories\Schedule;

use App\Models\Schedule;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    protected $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function create(array $data)
    {
        return $this->schedule->create($data);
    }

    public function updateOrCreate(array $attributes, array $data)
    {
        return $this->schedule->updateOrCreate($attributes, $data);
    }

    public function getLastRaceKey()
    {
        return $this->schedule->orderBy('created_at', 'desc')->orderBy('race_key', 'desc')->first(['race_key']);
    }

    public function getLastRaceDate()
    {
        return $this->schedule->orderBy('date', 'desc')->first(['date']);
    }

    public function getStatus($raceKey)
    {
        return $this->schedule->where('race_key', $raceKey)->first(['status']);
    }
}
