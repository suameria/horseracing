<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NetkeibaServiceProvider extends ServiceProvider
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
            'Netkeiba' => [
                'service'   => \App\Services\Scraping\Netkeiba\NetkeibaService::class,
                'interface' => \App\Services\Scraping\Netkeiba\NetkeibaServiceInterface::class,
            ],
            'Race' => [
                'service'   => \App\Services\Scraping\Netkeiba\Race\RaceService::class,
                'interface' => \App\Services\Scraping\Netkeiba\Race\RaceServiceInterface::class,
            ],
            'Horse' => [
                'service'   => \App\Services\Scraping\Netkeiba\Horse\HorseService::class,
                'interface' => \App\Services\Scraping\Netkeiba\Horse\HorseServiceInterface::class,
            ],
            'Jockey' => [
                'service'   => \App\Services\Scraping\Netkeiba\Jockey\JockeyService::class,
                'interface' => \App\Services\Scraping\Netkeiba\Jockey\JockeyServiceInterface::class,
            ],
            'Trainer' => [
                'service'   => \App\Services\Scraping\Netkeiba\Trainer\TrainerService::class,
                'interface' => \App\Services\Scraping\Netkeiba\Trainer\TrainerServiceInterface::class,
            ],

        ];

        foreach ($services as $service) {
            $this->app->bind($service['interface'],$service['service']);
        }
    }
}
