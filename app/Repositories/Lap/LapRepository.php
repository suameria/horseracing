<?php

namespace App\Repositories\Lap;

use App\Models\Lap;

class LapRepository implements LapRepositoryInterface
{
    protected $lap;

    public function __construct(Lap $lap)
    {
        $this->lap = $lap;
    }

    public function create(array $data)
    {
        return $this->lap->create($data);
    }

}
