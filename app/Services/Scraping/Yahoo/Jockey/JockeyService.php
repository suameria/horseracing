<?php

namespace App\Services\Scraping\Yahoo\Jockey;

use App\Repositories\Jockey\JockeyRepositoryInterface;

class JockeyService implements JockeyServiceInterface
{
    protected $jockeyRepository;

    public function __construct(JockeyRepositoryInterface $jockeyRepository)
    {
        $this->jockeyRepository = $jockeyRepository;
    }

    public function create(array $data)
    {
        return $this->jockeyRepository->create($data);
    }
}
