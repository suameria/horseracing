<?php

namespace App\Services\Scraping\Netkeiba\Jockey;

class JockeyService implements JockeyServiceInterface
{
    private $data = [
        'name'            => null,
        'name_kana'       => null,
        'blood'           => null,
        'height'          => null,
        'weight'          => null,
        'training_center' => null,
        'belonging'       => null,
        'hometown'        => null,
        'birthday'        => null,
        'trainer_key'     => null,
    ];

    public function formatJockeyData($crawler)
    {
        $crawler->filter('.db_head_name.fc')->each(function($element) {
            // 騎手名
            if (count($element->filter('h1'))) {

                $this->data['name'] = str_replace(' ', '', explode("\n", trim(mb_convert_kana($element->filter('h1')->text(), 's')))[0]);
                if (isset(explode("\n", trim(mb_convert_kana($element->filter('h1')->text(), 's')))[1])) {
                    $this->data['name_kana'] = removeBrackets(explode("\n", trim(mb_convert_kana($element->filter('h1')->text(), 's')))[1]);
                }
            }

            // 誕生日、厩舎、所属
            if (count($element->filter('.txt_01'))) {
                $text = trim($element->filter('.txt_01')->text());
                $explode = explode("\n", $text);

                $this->data['birthday']     = str_replace('/', '-', $explode[0]);

                // 海外選手、フリー、フリー以外
                if (isset($explode[2]) && mb_strpos($explode[2], '海外') !== false) {
                    // 海外 ex http://db.netkeiba.com/jockey/profile/05517/
                    $this->data['belonging'] = str_replace(' ', '', $explode[2]);
                } elseif (isset($explode[2]) && mb_strpos($explode[2], ']') !== false) {
                    // フリー ex http://db.netkeiba.com/jockey/profile/05339/
                    $this->data['training_center'] = str_replace('[', '', explode(']', $explode[2])[0]);
                    $this->data['belonging']       = str_replace(' ', '', explode(']', $explode[2])[1]);
                } elseif (isset($explode[1]) && mb_strpos($explode[1], ']') !== false) {
                    // NOTフリー ex http://db.netkeiba.com/jockey/profile/05386/
                    $this->data['training_center'] = str_replace('[', '', explode(']', $explode[1])[0]);
                    $this->data['belonging']       = str_replace(' ', '', explode(']', $explode[1])[1]);
                }

            }

            // 調教師キー
            if (count($element->filter('.txt_01')->filter('a'))) {
                $this->data['trainer_key'] = explode('/', $element->filter('.txt_01')->filter('a')->attr('href'))[2];
            }
        });

        $crawler->filter('.db_table_s table')->eq(0)->each(function($element) {

            if (count($element->filter('th')->eq(0))) {
                if ($element->filter('th')->eq(0)->text() !== '出身地') return;
                $this->data['hometown'] = $element->filter('td')->eq(0)->text();
            }

            if (count($element->filter('th')->eq(1))) {
                if ($element->filter('th')->eq(1)->text() !== '血液型') return;
                $this->data['blood'] = $element->filter('td')->eq(1)->text();
            }

            if (count($element->filter('th')->eq(2))) {
                if ($element->filter('th')->eq(2)->text() !== '身長') return;
                $this->data['height'] = $element->filter('td')->eq(2)->text();
            }

            if (count($element->filter('th')->eq(3))) {
                if ($element->filter('th')->eq(3)->text() !== '体重') return;
                $this->data['weight'] = $element->filter('td')->eq(3)->text();
            }
        });

        return $this->data;
    }
}
