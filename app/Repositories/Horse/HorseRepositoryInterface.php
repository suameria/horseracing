<?php

namespace App\Repositories\Horse;

interface HorseRepositoryInterface
{
    public function create(array $data);

    public function updateOrCreate($horseKey, array $save);

    public function getHorseByHorseKey($horseKey);

    public function getNameHorseKeys();

}