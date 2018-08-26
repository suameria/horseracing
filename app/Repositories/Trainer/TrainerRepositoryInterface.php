<?php

namespace App\Repositories\Trainer;

interface TrainerRepositoryInterface
{
    public function create(array $data);

    public function updateOrCreate($trainerKey, array $save);

    public function getTrainerByTrainerKey($trainerKey);

    public function getNameTrainerKeys();
}