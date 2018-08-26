<?php

namespace App\Repositories\Jockey;

interface JockeyRepositoryInterface
{
    public function create(array $data);

    public function updateOrCreate($jockeyKey, array $save);

    public function getJockeyByJockeyKey($jockeyKey);

    public function getNameJockeyKeys();
}