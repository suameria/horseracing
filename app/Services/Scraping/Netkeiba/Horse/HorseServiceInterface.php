<?php

namespace App\Services\Scraping\Netkeiba\Horse;

interface HorseServiceInterface
{
    public function formatHorseData($crawler);
}