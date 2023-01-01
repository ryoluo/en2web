<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            '交換留学',
            'ショートビジット',
            '院留学',
            '海外インターン',
            'JENESYS',
            '留学準備',
            'IELTS',
            'TOEFL',
            '理系',
            'トビタテ！',
            'その他奨学金',
            'OBOG投稿',
            '就活・仕事',
            '思考の共有',
            'IT',
            'Tips',
            '本・映画',
            'ネット記事',
        ];
        // foreach ($tags as $tag) {
        //     DB::table('tags')->insert('tag');
        // }
        $now = \Carbon\Carbon::now();

        for ($i = 0; $i < count($tags); $i++) {
            $tag = [
                'name' => $tags[$i],
                'created_at' => $now,
                'updated_at' => $now,
            ];
            DB::table('tags')->insert($tag);
        }
    }
}
