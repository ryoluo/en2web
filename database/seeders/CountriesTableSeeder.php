<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            'アメリカ',
            'イギリス',
            'カナダ',
            'オーストラリア',
            'ニュージーランド',
            '中国',
            '台湾',
            '香港',
            'シンガポール',
            'ベトナム',
            'フィリピン',
            'ミャンマー',
            'トンガ',
            'パラグアイ',
            'チェコ',
            'ハンガリー',
            'ドイツ',
            'オランダ',
            'フィンランド',
            'スイス',
            'フランス',
            '日本',
            '韓国',
            'エジプト',
            'モンゴル',
        ];

        $english_name = [
            'us',
            'uk',
            'canada',
            'australia',
            'newzealand',
            'china',
            'taiwan',
            'hongkong',
            'singapore',
            'vietnam',
            'philippines',
            'myanmar',
            'tonga',
            'paraguay',
            'czeck',
            'hungary',
            'germany',
            'netherlands',
            'finland',
            'switzerland',
            'france',
            'japan',
            'korea',
            'egypt',
            'mongolia',
        ];


        $now = \Carbon\Carbon::now();

        for ($i = 0; $i < count($countries); $i++) {
            $country = [
                'name' => $countries[$i],
                'english_name' => $english_name[$i],
                'created_at' => $now,
                'updated_at' => $now,
            ];
            DB::table('countries')->insert($country);
        }
    }
}
