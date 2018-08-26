<?php

namespace App\Services\Scraping\Yahoo\Horse;

use App\Repositories\Horse\HorseRepositoryInterface;

class HorseService implements HorseServiceInterface
{
    protected $horseRepository;

    public function __construct(HorseRepositoryInterface $horseRepository)
    {
        $this->horseRepository = $horseRepository;
    }

    public function create(array $data)
    {
        return $this->horseRepository->create($data);
    }
}
