<?php
/**
 * 定数管理
 */
return [

    'disabled' => 0,
    'enabled'  => 1,

    'day_of_week' => [
        '日',
        '月',
        '火',
        '水',
        '木',
        '金',
        '土',
    ],

    'status' => [
        'schedule' => [
            'before' => 0, // レース結果前
            'after'  => 1, // レース結果後
        ],
        'race' => [
            'regist' => 0, // 特別登録
            'denma'  => 1, // 出走予定
            'result' => 2, // 出走確定
        ],
    ],

    'places' => [
        '01' => '札幌',
        '02' => '函館',
        '03' => '福島',
        '04' => '新潟',
        '05' => '東京',
        '06' => '中山',
        '07' => '中京',
        '08' => '京都',
        '09' => '阪神',
        '10' => '小倉',
    ],

    'grade' => [
        1 => 'G1',
        2 => 'G2',
        3 => 'G3',
        4 => 'JG1',
        5 => 'JG2',
        6 => 'JG3',
    ],

    'grade_param' => [
        'All'  => 'All',
        'G123' => '重賞',
        'G1'   => 'G1',
        'G2'   => 'G2',
        'G3'   => 'G3',
        'NO'   => '重賞以外',
    ],

    'week' => [
        '日',
        '月',
        '火',
        '水',
        '木',
        '金',
        '土',
    ]

];