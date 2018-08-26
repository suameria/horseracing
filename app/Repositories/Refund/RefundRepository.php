<?php

namespace App\Repositories\Refund;

use App\Models\Refund;

class RefundRepository implements RefundRepositoryInterface
{
    protected $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

    public function create(array $data)
    {
        return $this->refund->create($data);
    }

}
