<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');

        for ($i = 0; $i < 10; $i++) {
            DB::table('orders')->insert([
                'user_id' => rand(1, 5),
                'item_id' => 1,
                'payment' => 'コンビニ払い',
                'postcode' => $faker->postcode(),
                'address' => $faker->prefecture() . $faker->city() . $faker->streetAddress(),
            ]);
        }
    }
}
