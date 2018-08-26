<?php

namespace App\Repositories\Corner;

use App\Models\Corner;

class CornerRepository implements CornerRepositoryInterface
{
    protected $corner;

    public function __construct(Corner $corner)
    {
        $this->corner = $corner;
    }

    public function create(array $data)
    {
        return $this->corner->create($data);
    }

}
