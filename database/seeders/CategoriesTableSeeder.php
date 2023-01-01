<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            '月一報告',
            '語学学習・試験',
            '奨学金・留学申請',
            '就活・仕事',
            '本・映画・記事',
            '思考の共有・その他',
        ];
        // foreach ($tags as $tag) {
        //     DB::table('tags')->insert('tag');
        // }
        $now = \Carbon\Carbon::now();

        for ($i = 0; $i < count($categories); $i++) {
            $category = [
                'name' => $categories[$i],
                'created_at' => $now,
                'updated_at' => $now,
            ];
            DB::table('categories')->insert($category);
        }
    }
}
