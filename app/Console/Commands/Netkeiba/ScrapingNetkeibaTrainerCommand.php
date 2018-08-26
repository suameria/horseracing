<?php

namespace App\Console\Commands\Netkeiba;

use Log;
use Goutte\Client;
use App\Services\Scraping\Netkeiba\Trainer\TrainerServiceInterface;
use App\Repositories\Trainer\TrainerRepositoryInterface;
use Illuminate\Console\Command;

class ScrapingNetkeibaTrainerCommand extends Command
{
    protected $signature = 'netkeiba:trainer';
    protected $description = 'Get netkeiba trainer data by scraping';

    protected $client;
    protected $trainerService;
    protected $trainerRepository;

    public function __construct(
        Client                     $client,
        TrainerServiceInterface    $trainerService,
        TrainerRepositoryInterface $trainerRepository
    ) {
        parent::__construct();
        $this->client            = $client;
        $this->trainerService    = $trainerService;
        $this->trainerRepository = $trainerRepository;
    }

    public function handle()
    {
        $nameTrainerKeys = $this->trainerRepository->getNameTrainerKeys();
        foreach ($nameTrainerKeys as $trainer) {
            $trainerKey = $trainer->trainer_key;
            $url        = config('netkeiba.urls.trainer') . $trainerKey;

            $this->progressBar(config('netkeiba.sleep'));
            $this->question("Trainer URL: $url");
            Log::info('Trainer', ['URL' => $url]);

            $crawler     = $this->client->request('GET', $url);
            $trainerData = $this->trainerService->formatTrainerData($crawler);

            $this->trainerRepository->updateOrCreate($trainerKey, $trainerData);
            $this->question("UPDATE: {$trainerData['name']}");
            Log::info("UPDATE: {$trainerData['name']}");
        }
    }

    private function progressBar($maxSec, $startSentence = "Waiting...", $endSentence = "Starting...")
    {
        echo PHP_EOL;
        $this->comment($startSentence);

        $progress = $this->output->createProgressBar($maxSec);
        $progress->setBarCharacter("■");
        $progress->setEmptyBarCharacter(" ");

        $range = range(1, $maxSec);
        foreach ($range as $i) {
            sleep(1);
            $progress->advance();
        }
        $progress->finish();

        echo PHP_EOL;
        $this->comment($endSentence);
        echo PHP_EOL;
    }
}
