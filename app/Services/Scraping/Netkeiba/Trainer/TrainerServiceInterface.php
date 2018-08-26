<?php

namespace App\Services\Scraping\Netkeiba\Trainer;

interface TrainerServiceInterface
{
    public function formatTrainerData($crawler);
}