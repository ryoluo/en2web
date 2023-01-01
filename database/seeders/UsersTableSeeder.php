<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();
        $u = [
            'name' => 'Ryo Kobayashi',
            'email' => 'test@example.com',
            'password' => Hash::make('testtest'),
            'slack_id' => config('const.SLACK_RYO_KOBAYASHI'),
        ];
        DB::table('users')->insert($u);
    }
}
