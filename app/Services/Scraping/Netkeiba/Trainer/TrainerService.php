<?php

namespace App\Services\Scraping\Netkeiba\Trainer;

use App\Repositories\Trainer\TrainerRepositoryInterface;

class TrainerService implements TrainerServiceInterface
{
    private $data = [
        'name'            => null,
        'name_kana'       => null,
        'training_center' => null,
        'hometown'        => null,
        'birthday'        => null,
    ];

    public function formatTrainerData($crawler)
    {
        $crawler->filter('.db_head_name.fc')->each(function($element) {
            // 調教師名
            if (count($element->filter('h1'))) {

                $this->data['name'] = str_replace(' ', '', explode("\n", trim(mb_convert_kana($element->filter('h1')->text(), 's')))[0]);
                if (isset(explode("\n", trim(mb_convert_kana($element->filter('h1')->text(), 's')))[1])) {
                    $this->data['name_kana'] = removeBrackets(explode("\n", trim(mb_convert_kana($element->filter('h1')->text(), 's')))[1]);
                }
            }

            // 誕生日、厩舎、所属
            if (count($element->filter('.txt_01'))) {
                $text = trim($element->filter('.txt_01')->text());

                if (strpos($text, "\n")) {
                    $explode = explode("\n", $text);

                    if (isset($explode[0])) {
                        $this->data['birthday']     = str_replace('/', '-', $explode[0]);
                    }

                    if (isset($explode[1])) {
                        $this->data['training_center'] = str_replace(' ', '', $explode[1]);
                    }
                } else {
                    $this->data['training_center'] = str_replace(' ', '', $text);
                }
            }
        });

        $crawler->filter('.db_table_s table')->eq(0)->each(function($element) {

            if (count($element->filter('th')->eq(0))) {
                if ($element->filter('th')->eq(0)->text() !== '出身地') return;
                $this->data['hometown'] = $element->filter('td')->eq(0)->text();
            }
        });

        return $this->data;
    }
}
