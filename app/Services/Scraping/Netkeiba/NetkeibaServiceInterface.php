<?php

namespace App\Services\Scraping\Netkeiba;

interface NetkeibaServiceInterface
{
    public function updateOrCreateRaceResult($data);
}