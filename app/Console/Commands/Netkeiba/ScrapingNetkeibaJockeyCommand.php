<?php

namespace App\Console\Commands\Netkeiba;

use Log;
use Goutte\Client;
use App\Services\Scraping\Netkeiba\Jockey\JockeyServiceInterface;
use App\Repositories\Jockey\JockeyRepositoryInterface;
use Illuminate\Console\Command;

class ScrapingNetkeibaJockeyCommand extends Command
{
    protected $signature = 'netkeiba:jockey';
    protected $description = 'Get netkeiba jockey data by scraping';

    protected $client;
    protected $jockeyService;
    protected $jockeyRepository;

    public function __construct(
        Client                    $client,
        JockeyServiceInterface    $jockeyService,
        JockeyRepositoryInterface $jockeyRepository
    ) {
        parent::__construct();
        $this->client           = $client;
        $this->jockeyService    = $jockeyService;
        $this->jockeyRepository = $jockeyRepository;
    }

    public function handle()
    {
        $nameJockeyKeys = $this->jockeyRepository->getNameJockeyKeys();
        foreach ($nameJockeyKeys as $jockey) {
            $jockeyKey = $jockey->jockey_key;
            $url       = config('netkeiba.urls.jockey') . $jockeyKey;

            $this->progressBar(config('netkeiba.sleep'));
            $this->question("Jockey URL: $url");
            Log::info('Jockey', ['URL' => $url]);

            $crawler   = $this->client->request('GET', $url);
            $jockeyData = $this->jockeyService->formatJockeyData($crawler);

            $this->jockeyRepository->updateOrCreate($jockeyKey, $jockeyData);
            $this->question("UPDATE: {$jockeyData['name']}");
            Log::info("UPDATE: {$jockeyData['name']}");
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
