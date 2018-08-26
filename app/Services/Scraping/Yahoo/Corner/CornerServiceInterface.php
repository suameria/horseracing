<?php

namespace App\Services\Scraping\Yahoo\Corner;

interface CornerServiceInterface
{
    public function getCorner($crawler, $raceKey);
}