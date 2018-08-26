<?php

namespace App\Services\Scraping\Yahoo\Refund;

interface RefundServiceInterface
{
    public function getRefund($crawler, $raceKey);
}