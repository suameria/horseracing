<?php

namespace App\Repositories\Trainer;

use App\Models\Trainer;

class TrainerRepository implements TrainerRepositoryInterface
{
    protected $trainer;

    public function __construct(Trainer $trainer)
    {
        $this->trainer = $trainer;
    }

    public function create(array $data)
    {
        return $this->trainer->create($data);
    }

    public function updateOrCreate($trainerKey, array $save)
    {
        return $this->trainer->updateOrCreate(['trainer_key' => $trainerKey], $save);
    }

    public function getTrainerByTrainerKey($trainerKey)
    {
        return $this->trainer->where('trainer_key', $trainerKey)->first();
    }

    public function getNameTrainerKeys()
    {
        $select = config('column.trainers.name_trainer_key');
        return $this->trainer->select($select)
                    ->whereNull('name')
                    ->groupBy($select)
                    ->orderBy('trainer_key', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}
