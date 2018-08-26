<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
        $repositories = [
            'Calendar' => [
                'repository' => \App\Repositories\Calendar\CalendarRepository::class,
                'interface'  => \App\Repositories\Calendar\CalendarRepositoryInterface::class
            ],
            'Schedule' => [
                'repository' => \App\Repositories\Schedule\ScheduleRepository::class,
                'interface'  => \App\Repositories\Schedule\ScheduleRepositoryInterface::class
            ],
            'Race' => [
                'repository' => \App\Repositories\Race\RaceRepository::class,
                'interface'  => \App\Repositories\Race\RaceRepositoryInterface::class
            ],
            'Refund' => [
                'repository' => \App\Repositories\Refund\RefundRepository::class,
                'interface'  => \App\Repositories\Refund\RefundRepositoryInterface::class
            ],
            'Lap' => [
                'repository' => \App\Repositories\Lap\LapRepository::class,
                'interface'  => \App\Repositories\Lap\LapRepositoryInterface::class
            ],
            'Corner' => [
                'repository' => \App\Repositories\Corner\CornerRepository::class,
                'interface'  => \App\Repositories\Corner\CornerRepositoryInterface::class
            ],
            'Horse' => [
                'repository' => \App\Repositories\Horse\HorseRepository::class,
                'interface'  => \App\Repositories\Horse\HorseRepositoryInterface::class
            ],
            'Jockey' => [
                'repository' => \App\Repositories\Jockey\JockeyRepository::class,
                'interface'  => \App\Repositories\Jockey\JockeyRepositoryInterface::class
            ],
            'Trainer' => [
                'repository' => \App\Repositories\Trainer\TrainerRepository::class,
                'interface'  => \App\Repositories\Trainer\TrainerRepositoryInterface::class
            ],

        ];

        foreach ($repositories as $repo) {
            $this->app->bind($repo['interface'], $repo['repository']);
        }
    }
}
