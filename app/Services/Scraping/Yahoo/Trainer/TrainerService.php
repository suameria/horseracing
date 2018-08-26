<?php

namespace App\Services\Scraping\Yahoo\Trainer;

use App\Repositories\Trainer\TrainerRepositoryInterface;

class TrainerService implements TrainerServiceInterface
{
    protected $trainerRepository;

    public function __construct(TrainerRepositoryInterface $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }

    public function create(array $data)
    {
        return $this->trainerRepository->create($data);
    }
}
