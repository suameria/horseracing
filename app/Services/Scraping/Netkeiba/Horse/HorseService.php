<?php

namespace App\Services\Scraping\Netkeiba\Horse;

class HorseService implements HorseServiceInterface
{
    private $data = [
        'name'        => null,
        'name_detail' => null,
        'sex'         => null,
        'coat_color'  => null,
        'birthday'    => null,
        'owner'       => null,
        'breeder'     => null,
        'hometown'    => null,
        'status'      => null,
        'f_horse_key' => null,
        'm_horse_key' => null,
        'trainer_key' => null,
    ];

    public function formatHorseData($crawler)
    {
        $this->data['name'] = explode(' | ', $crawler->filter('title')->text())[0];

        $crawler->filter('.horse_title')->each(function($element) {
            // 競走馬名
            if (count($element->filter('h1'))) {
                $this->data['name_detail'] = trim(mb_convert_kana($element->filter('h1')->text(), 's'));
            }

            // 登録種別、性別、毛色
            if (count($element->filter('.txt_01'))) {
                $text = trim($element->filter('.txt_01')->text());
                $explode = explode('　', $text);
                $this->data['status']     = $explode[0] ?: null;
                $this->data['sex']        = mb_substr($explode[1], 0, 1) ?: null;
                $this->data['coat_color'] = $explode[2] ?: null;
            }
        });

        /**
         * memo tr複数個に対してeachするイメージ
         */
        $crawler->filter('table.db_prof_table tr')->each(function($element) {
            if (count($element->filter('th')) && count($element->filter('td'))) {
                // 生年月日
                if (trim($element->filter('th')->text()) == '生年月日') {
                    $date = trim($element->filter('td')->text());
                    if (mb_strpos($date, '年') !== false) {
                        if (mb_strpos($date, '月') !== false && mb_strpos($date, '日') !== false) {
                            $date = str_replace('年', '-', $date);
                            $date = str_replace('月', '-', $date);
                            $date = str_replace('日', '', $date);
                        } else {
                            $date = str_replace('年', '', $date) . '-01-01';
                        }
                    } else {
                        $date = null;
                    }
                    $this->data['birthday'] = $date;
                }

                // 調教師キー
                if (count($element->filter('td')->filter('a'))) {
                    $this->data['trainer_key'] = explode('/', trim($element->filter('td')->filter('a')->attr('href')))[2];
                }

                // 馬主
                if (trim($element->filter('th')->text()) == '馬主') {
                    $this->data['owner'] = trim($element->filter('td')->text()) ?: null;
                }

                // 生産者
                if (trim($element->filter('th')->text()) == '生産者') {
                    $this->data['breeder'] = trim($element->filter('td')->text()) ?: null;
                }

                // 産地
                if (trim($element->filter('th')->text()) == '産地') {
                    $this->data['hometown'] = trim($element->filter('td')->text()) ?: null;
                }
            }
        });

        $crawler->filter('.db_prof_area_02 table')->eq(1)->each(function($element) {
            // 父
            if (count($element->filter('tr td')->eq(0)->filter('a'))) {
                $link = $element->filter('tr td')->eq(0)->filter('a')->attr('href');
                $this->data['f_horse_key'] = explode('/', $link)[3];
            }

            // 母
            if (count($element->filter('tr td')->eq(3)->filter('a'))) {
                $link = $element->filter('tr td')->eq(3)->filter('a')->attr('href');
                $this->data['m_horse_key'] = explode('/', $link)[3];
            }
        });

        return $this->data;
    }
}
