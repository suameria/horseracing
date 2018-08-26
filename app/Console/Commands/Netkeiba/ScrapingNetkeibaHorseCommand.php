<?php

namespace App\Console\Commands\Netkeiba;

use Goutte\Client;
use App\Services\Scraping\Netkeiba\Horse\HorseServiceInterface;
use App\Repositories\Horse\HorseRepositoryInterface;
use Illuminate\Console\Command;

class ScrapingNetkeibaHorseCommand extends Command
{
    protected $signature = 'netkeiba:horse';
    protected $description = 'Get netkeiba horse data by scraping';

    protected $client;
    protected $horseService;
    protected $horseRepository;

    public function __construct(
        Client                   $client,
        HorseServiceInterface    $horseService,
        HorseRepositoryInterface $horseRepository
    ) {
        parent::__construct();
        $this->client          = $client;
        $this->horseService    = $horseService;
        $this->horseRepository = $horseRepository;
    }

    public function handle()
    {
        $nameHorseKeys = $this->horseRepository->getNameHorseKeys();
        foreach ($nameHorseKeys as $horse) {

            $horseKey = $horse->horse_key;
            $url      = config('netkeiba.urls.horse') . $horseKey;

            usleep(100000);

            $this->comment("$url");

            $crawler   = $this->client->request('GET', $url);
            $horseData = $this->horseService->formatHorseData($crawler);

            $fHorseKey = $horseData['f_horse_key'];
            if (empty($this->horseRepository->getHorseByHorseKey($fHorseKey))) {
                $this->horseRepository->create(['horse_key' => $fHorseKey]);
                $this->comment("FATHER HORSE KEY: $fHorseKey");
            }

            $mHorseKey = $horseData['m_horse_key'];
            if (empty($this->horseRepository->getHorseByHorseKey($mHorseKey))) {
                $this->horseRepository->create(['horse_key' => $mHorseKey]);
                $this->comment("MOTHER HORSE KEY: $mHorseKey");
            }

            $this->horseRepository->updateOrCreate($horseKey, $horseData);
            $this->question("{$horseData['name']}");
        }
    }
}
