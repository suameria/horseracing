<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ScrapingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $services = [
            'Yahoo' => [
                'service'   => \App\Services\Scraping\Yahoo\YahooService::class,
                'interface' => \App\Services\Scraping\Yahoo\YahooServiceInterface::class,
            ],
            'Schedule' => [
                'service'   => \App\Services\Scraping\Yahoo\Schedule\ScheduleService::class,
                'interface' => \App\Services\Scraping\Yahoo\Schedule\ScheduleServiceInterface::class,
            ],
            'Race' => [
                'service'   => \App\Services\Scraping\Yahoo\Race\RaceService::class,
                'interface' => \App\Services\Scraping\Yahoo\Race\RaceServiceInterface::class,
            ],
            'Refund' => [
                'service'   => \App\Services\Scraping\Yahoo\Refund\RefundService::class,
                'interface' => \App\Services\Scraping\Yahoo\Refund\RefundServiceInterface::class,
            ],
            'Lap' => [
                'service'   => \App\Services\Scraping\Yahoo\Lap\LapService::class,
                'interface' => \App\Services\Scraping\Yahoo\Lap\LapServiceInterface::class,
            ],
            'Corner' => [
                'service'   => \App\Services\Scraping\Yahoo\Corner\CornerService::class,
                'interface' => \App\Services\Scraping\Yahoo\Corner\CornerServiceInterface::class,
            ],
            'Horse' => [
                'service'   => \App\Services\Scraping\Yahoo\Horse\HorseService::class,
                'interface' => \App\Services\Scraping\Yahoo\Horse\HorseServiceInterface::class,
            ],
            'Jockey' => [
                'service'   => \App\Services\Scraping\Yahoo\Jockey\JockeyService::class,
                'interface' => \App\Services\Scraping\Yahoo\Jockey\JockeyServiceInterface::class,
            ],
            'Trainer' => [
                'service'   => \App\Services\Scraping\Yahoo\Trainer\TrainerService::class,
                'interface' => \App\Services\Scraping\Yahoo\Trainer\TrainerServiceInterface::class,
            ],
        ];

        foreach ($services as $service) {
            $this->app->bind($service['interface'],$service['service']);
        }
    }
}
