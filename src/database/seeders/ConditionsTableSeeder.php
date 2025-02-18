<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conditions')->insert([
            [
                'condition' => '良好',
            ],
            [
                'conditions' => '目立った汚れなし',
            ],
            [
                'conditions' => 'やや傷や汚れあり',
            ],
            [
                'conditions' => '状態が悪い',
            ],
        ]);
    }
}
