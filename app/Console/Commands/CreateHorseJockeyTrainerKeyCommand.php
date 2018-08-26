<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\Scraping\Yahoo\Race\RaceServiceInterface;
use App\Repositories\Horse\HorseRepositoryInterface;
use App\Repositories\Jockey\JockeyRepositoryInterface;
use App\Repositories\Trainer\TrainerRepositoryInterface;

class CreateHorseJockeyTrainerKeyCommand extends Command
{
    protected $signature = 'keiba:create-horse-jockey-trainer-key';
    protected $description = 'Create Horse Jockey Trainer Key';

    protected $raceService;
    protected $horseRepository;
    protected $jockeyRepository;
    protected $trainerRepository;

    public function __construct(
        RaceServiceInterface       $raceService,
        HorseRepositoryInterface   $horseRepository,
        JockeyRepositoryInterface  $jockeyRepository,
        TrainerRepositoryInterface $trainerRepository
    ) {
        parent::__construct();
        $this->raceService       = $raceService;
        $this->horseRepository   = $horseRepository;
        $this->jockeyRepository  = $jockeyRepository;
        $this->trainerRepository = $trainerRepository;
    }

    public function handle()
    {
        $this->question('START CREATE HORSE KEY');
        $this->createHorseKey();
        $this->question('FINISH CREATE HORSE KEY');
        echo PHP_EOL;

        $this->question('START CREATE JOCKEY KEY');
        $this->createJockeyKey();
        $this->question('FINISH CREATE JOCKEY KEY');
        echo PHP_EOL;

        $this->question('START CREATE TRAINER KEY');
        $this->createTrainerKey();
        $this->question('FINISH CREAT TRAINER KEY');
    }

    private function createHorseKey()
    {
        $horses   = $this->raceService->getHorseKey();
        $progress = $this->output->createProgressBar($horses->count());
        $progress->setBarCharacter("■");
        $progress->setEmptyBarCharacter(" ");
        foreach ($horses as $horse) {
            $progress->advance();
            $this->horseRepository->updateOrCreate($horse->horse_key, ['horse_key' => $horse->horse_key]);
        }
        $progress->finish();
        echo PHP_EOL;
    }

    private function createJockeyKey()
    {
        $jockeys  = $this->raceService->getJockeyKey();
        $progress = $this->output->createProgressBar($jockeys->count());
        $progress->setBarCharacter("■");
        $progress->setEmptyBarCharacter(" ");
        foreach ($jockeys as $jockey) {
            $progress->advance();
            $this->jockeyRepository->updateOrCreate($jockey->jockey_key, ['jockey_key' => $jockey->jockey_key]);
        }
        $progress->finish();
        echo PHP_EOL;
    }

    private function createTrainerKey()
    {
        $trainers = $this->raceService->getTrainerKey();
        $progress = $this->output->createProgressBar($trainers->count());
        $progress->setBarCharacter("■");
        $progress->setEmptyBarCharacter(" ");
        foreach ($trainers as $trainer) {
            $progress->advance();
            $this->trainerRepository->updateOrCreate($trainer->trainer_key, ['trainer_key' => $trainer->trainer_key]);
        }
        $progress->finish();
        echo PHP_EOL;
    }
}
