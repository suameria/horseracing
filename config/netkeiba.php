<?php
/**
 * netkeiba constants
 */
return [
    // sleep
    'sleep' => 1,

    'urls' => [
        'official' => 'http://www.netkeiba.com/',
        'race'     => 'http://race.netkeiba.com/',
        'horse'    => 'http://db.netkeiba.com/horse/',
        'jockey'   => 'http://db.netkeiba.com/jockey/profile/',
        'trainer'  => 'http://db.netkeiba.com/trainer/profile/',
    ],

    'places' => [
        1  => '札幌',
        2  => '函館',
        3  => '福島',
        4  => '新潟',
        5  => '東京',
        6  => '中山',
        7  => '中京',
        8  => '京都',
        9  => '阪神',
        10 => '小倉',
    ],

    'refund' => [
        1 => '単勝',
        2 => '複勝',
        3 => '枠連',
        4 => '馬連',
        5 => 'ワイド',
        6 => '馬単',
        7 => '3連複',
        8 => '3連単',
    ],

    'race_card_eq_number' => [
        'on_no_weight' => [
            'post_position' => 0,
            'horse_number'  => 1,
            'horse_sex_age' => 4,
            'weight'        => 5,
            'jockey_key'    => 6,
            'trainer_key'   => 7,
            'odds'          => 8,
            'favorite'      => 9,
        ],
        'on' => [
            'post_position' => 0,
            'horse_number'  => 1,
            'horse_sex_age' => 4,
            'weight'        => 5,
            'jockey_key'    => 6,
            'trainer_key'   => 7,
            'horse_weight'  => 8,
            'odds'          => 9,
            'favorite'      => 10,
        ],
        'off' => [
            'horse_sex_age' => 2,
            'weight'        => 3,
            'jockey_key'    => 4,
            'trainer_key'   => 5,
            'odds'          => 6,
            'favorite'      => 7,
        ],
    ],
];
